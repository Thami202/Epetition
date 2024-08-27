<?php namespace AhmadFatoni\ApiGenerator\Controllers\api;

use File;
use Event;
use SystemException;
use ReflectionClass;
use ApplicationException;
use Backend\Classes\Controller;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Models\Status;
use AhmadFatoni\ApiGenerator\Models\Conversation;

class ConversationController extends Controller {

	use \AhmadFatoni\ApiGenerator\traits\StandardAPIController;

	public $_model = 'AhmadFatoni\ApiGenerator\Models\Conversation';

	public $collection = 'AhmadFatoni\ApiGenerator\Resources\Conversation';

	protected $_key =0;

	public $permissions = [
		'enabled' => 0,
		'config' => '[{"id":"-1"}]'
	];

	public function __contruct()
	{
		parent::__construct();
	}

	public function startConversation(Request $request)
	{
		try {
			$model = new $this->_model;
			$conversation = $this->getOrCreateConversation($model, $request);
			
			return $this->prepareResponse(200, 'Success',[
				'id' => $conversation->id,
				'title' => $conversation->task['name'],
				'status' => $conversation->status,
				'started_at' => $conversation->started_at,
				'ended_at' => $conversation->ended_at,
				'phone_number' => $conversation->phone_number,
				'task' => $conversation->getCurrentActivity()
			]);
		}
		catch (ApplicationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}		
	}

	public function updateConversationTaskActivity(Request $request, $id)
	{
		try {
			$conversation = $this->_model::findorfail($id);
			list($activityIndex, $activity) = Arr::divide($conversation->getCurrentActivity());

			$activityIndex = Arr::first($activityIndex);
			$activity = Arr::first($activity);

			if(!$activity) {
				throw new ApplicationException('conversation complete', 400);
			}

			if(!$request->type) {
				throw new ApplicationException('The parameter "type" is required');
			}

			$task = $conversation->task;

			switch($request->type) {
				case 'file':
					$activity['value'] = Arr::add($activity['value'], $conversation->getActivityNextValueArrayIndex($activity), $request->value);
					$task['activities']['items'][$activityIndex] = $activity;

					$conversation->update([
						'task' => $task
					]);

					$conversation->refresh();
					return $this->getConversationNextFileActivity($conversation);
				break;

				case 'file_completed':
				default:
					if(!$request->value) {
						throw new ApplicationException(sprintf('The value for field %s is required', $activity['id']));
					}

					$activity['value'] = $request->value;
					$activity['is_current'] = false;
					$activity['status'] = true;

					if(($activityIndex+1) < count($conversation->task['activities']['items'])) {
						$task['activities']['items'][$activityIndex] = $activity;
						$next_activity = $task['activities']['items'][$activityIndex+1];
						$next_activity['is_current'] = true;			
						$task['activities']['items'][$activityIndex+1] = $next_activity;

						$conversation->update([
							'task' => $task
						]);

						$conversation->refresh();

						return $this->prepareResponse('200', 'Success', [
							'id' => $conversation->id,
							'title' => $conversation->task['name'],
							'status' => $conversation->status,
							'started_at' => $conversation->started_at,
							'ended_at' => $conversation->ended_at,
							'phone_number' => $conversation->phone_number,
							'task' => $conversation->getCurrentActivity(),
						]);
					}
					else {
						$task['activities']['items'][$activityIndex] = $activity;
						$conversation->update([
							'status_id' => Status::complete()->id,					
							'ended_at' => now(),
							'task' => $task
						]);

						$conversation = $conversation->refresh();

						// Conversation is complete. Now we have adequate information to create the requested model.

						$model = $this->createModelFromConversation($conversation);

						$conversation = $conversation->refresh();

						Event::fire('conversation.upload_media', ['conversation' => $conversation]);
						
						return $this->prepareResponse('200', 'Success',[
							'id' => $conversation->id,
							'title' => $conversation->task['name'],
							'status' => $conversation->status,
							'started_at' => $conversation->started_at,
							'ended_at' => $conversation->ended_at,
							'phone_number' => $conversation->phone_number,
							'task' => $conversation->getCurrentActivity(),
							'model' => [
								'id' => $model->id,
								'title' => $model->title,
								'reference' => $model->reference_number,
								'status' => $model->status->name
							]
						]);
					}
				break;
			}
			
		}
		catch (ApplicationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
	}

	public function myConversation(Request $request)
	{
		return $this->prepareResponse(200, 'Success', 
			$this->_model::where('phone_number', $request->phone_number)->where('status_id', Status::pending()->id)->first()
		);
	}

	protected function getConversationCurrentActivity($conversation)
	{
		$items = $conversation->task['activities']['items'];
		return Arr::first(Arr::where($items, function($activity, $key) {
			return $activity['is_current'];
		}));
	}


	protected function getOrCreateConversation($model, $request)
	{
		$targetModel = 'AhmadFatoni\\ApiGenerator\\models\\'.ucfirst($request->model);
		$filePath = File::symbolizePath('$/'.str_replace('\\', '/', $targetModel).'.php');

		if(!File::isFile($filePath)){
			throw new ApplicationException('Model type NOT found.');			
		}
		
		$targetModelObj = new $targetModel;

		/*if($this->hasPendingConversation($model, $request->phone_number, $targetModelObj::class))
			return $this->getUserConversation($model, $request->phone_number, $targetModelObj::class);

		$conversation = new Conversation([
			'name' => $request->name,
			'phone_number' => $request->phone_number,
			'task' => [
				'name' => 'Add new ' . (new ReflectionClass($targetModelObj))->getShortName(),
				'status' => 0,
				'activities' => $targetModelObj->getModelForm()
			],
			'chatable_type' => $targetModelObj::class
		]);

		$conversation->save();

		return $conversation;*/
	}

	protected function getUserConversation($model, $phone_number, $record_type)
	{
		return $model->where('phone_number', $phone_number)->where('chatable_type', $record_type)->where('status_id', Status::pending()->id)->first();
	}

	protected function hasPendingConversation($model, $phone_number, $record_type)
	{
		return $model->where('phone_number', $phone_number)
		->where('chatable_type', $record_type)
		->where('status_id', Status::pending()->id)->count() ? true : false;
	}

	protected function createModelFromConversation($conversation)
	{
		$targetModel = $conversation->chatable_type;
		$fields = [];

		$mediaFields = [];
		foreach($conversation->task['activities']['items'] as $key => $item) {
			if($item['type'] != 'file') {
				$fields[$item['id']] = $item['value'];
			}
			else if($item['type'] == 'file') {
				$mediaFields[$item['id']] = $item['value'];
			}
		}

		$sessionKey = uniqid('session_key', true);

		$targetModelObj = new $conversation->chatable_type($fields);
		$targetModelObj->conversations()->add($conversation, $sessionKey);
		$targetModelObj->save(null, $sessionKey);

		return $targetModelObj;
	}

	protected function getConversationNextFileActivity($conversation)
	{
		return $this->prepareResponse('200', 'Success', [
			'id' => $conversation->id,
			'title' => $conversation->task['name'],
			'status' => $conversation->status,
			'started_at' => $conversation->started_at,
			'ended_at' => $conversation->ended_at,
			'phone_number' => $conversation->phone_number,
			'task' => $conversation->getCurrentActivity(),
		]);
	}

}
