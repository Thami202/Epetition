<?php namespace Parliament\Submissions\Classes\Jobs;

use Log;
use Http;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Queue\Job;
use Parliament\Submissions\Models\Petition;
use Parliament\Submissions\Models\GlobalSetting;
use Parliament\Submissions\Models\Conversation;

class DocumentUploaded {
	/**
	 * @Type integer number of http retries before exception if thrown
	 */
	protected $retries = 3;

	/**
	 * @Type integer the interval between retries
	 */
	protected $sleep = 100;

	/**
	 * @Type string the Meta graph API access token
	 */
	protected $token = null;

	public function fire(Job $job, $data)
	{
		$this->token = GlobalSetting::get('metaAccessToken');
		$model = Petition::findOrFail($data['modelId']);

		try {

    		foreach(Arr::first($data['activity'])['value'] as $activity) {
    			$data = $this->getDownloadUrl($activity);
    			
    			// Continue to the next item when media object is null.
    			if(!$data) {
    				continue;
    			}

    			$response = Http::retry($this->retries, $this->sleep)->withToken($this->token)->get($data->url);    			

	    		$filename = $data->id . '.' . Arr::last(explode('/', $data->mime_type));

	    		$file = (new \System\Models\File)->fromData($response->body(), $filename);
	    		$file->is_public = true;
	    		$file->save();
	    		$model->documents()->add($file);
    		}    		
		}
		catch(Exception $e) {
			Log::error($e->getMessage());
		}

		$job->delete();
	}

	/**
	 * Download media metadata from Whatsapp
	 * @var string mediaId
	 * @return stdClass object media file metadata
	 */
	private function getDownloadUrl($mediaId)
	{
		try {
			$link = GlobalSetting::get('metaConfig') . $mediaId;
			$response = Http::retry($this->retries, $this->sleep)->withToken($this->token)->get($link);

			if($response->successful()) {
				return json_decode($response->body());
			}			
		}
		catch(Exception $e) {
			Log::error($e->getMessage());
			return null;
		}
	}
}