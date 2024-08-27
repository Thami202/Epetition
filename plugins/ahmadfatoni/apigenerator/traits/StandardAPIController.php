<?php namespace AhmadFatoni\ApiGenerator\traits;

use App;
use File;
use JWTAuth;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Provides API controller behavior.
 *
 * @package makgabophao\apibuilder
 * @author Makgabo Phao
 */
Trait StandardAPIController {


	/*
	* Returns all model records
	* @input $request
	* @return model records
	*/
	public function index(Request $request)
	{
		
		try {

			$permissions = $this->hasAccess(__FUNCTION__);
			
			if($request->has('filter')){
				$data = explode(';', $request->get('filter'));

				$queryString = '';
				foreach ($data as $value) {
					@list($column, $operator, $value) = explode(':', $value);
					$operator = $this->mapOperator($operator);

					if($operator == 'IN' || $operator == "NOT IN") {
						$queryString .= "`$column` ".$operator." $value AND ";
					}
					else{
						$queryString .= "`$column`".$operator."'$value'".' AND ';
					}
				}

				$queryString = trim($queryString, 'AND ');

				$orderByString = $request->has('order') ? $this->getOrderByString($request->get('order')) : 'created_at desc';

				$records = $this->_model::whereRaw($queryString)->orderByRaw($orderByString);
			}
			elseif ($request->has('order')) {
				$orderByString = $this->getOrderByString($request->get('order'));
				$records = $this->_model::orderByRaw($orderByString);				
			}
			else {
				$records = $this->_model::where('id','>', 0);
			}
			// Apply authorization filter before return results
			// $records = $this->applyAuthorizationFilter($records, $permissions);

			return $this->prepareResponse(200, 'success', $this->collection::collection($records->get()), $request->has('paginate'));
			
		}
		catch(AuthorizationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch (Exception $e) {
			return response()->json($e->getMessage());
		}
	}

	/*
	 * Save model
	 * @input $request
	 * @return saved model
	 */

	public function store(Request $request)
	{
		try {
			$arr = $request->except('token');

			$model = new $this->_model;
			while ( $data = current($arr)) {
	            $model->{key($arr)} = $data;
	            next($arr);
	        }

	        $model->save();
	      
	        $validation = \Validator::make($arr, $model->rules);

	        if( $validation->passes() ){
	            $model->save();
	            return $this->prepareResponse(201, 'created', [$model]);
	        }
	        else{
	            return $this->prepareResponse(400, 'fail', $validation->errors());
	        }
		}
		catch(AuthorizationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch (Exception $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
	}

	/*
	* Returns model with specified id
	* @input $id model id
	* @return $model the model identified by the $id
	*/
	public function show($id)
	{
		try {
			$model = $this->getModel($id, __FUNCTION__);				
			return new $this->collection($model);
		} 
		catch(ModelNotFoundException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch(AuthorizationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch (Exception $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
	}	

	/*
	* Updates the specified model
	* @input $id model id
	* @input $request data to apply to the model
	* @return $model updated
	*/
	public function update($id, Request $request)
	{
		try {
			$model = $this->getModel($id, __FUNCTION__);

			$arr = $request->except('token');

			while($data = current($arr)) {
	            $model->{key($arr)} = $data;
	            next($arr);
	        }
	        $model->group = $request->get('group');

			$status = $model->update();

			if( $status ){            
	            return $this->prepareResponse(200, 'success', [$model]);

	        }else{
	            return $this->prepareResponse(400, 'bad request', 'Error, data failed to update.');
	        }
			
		} 
		catch(ModelNotFoundException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch(AuthorizationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch (Exception $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
	
	}

	/*
	* Deletes or destroys the specified model
	* @input $id model id
	* @return null
	*/
	public function delete($id) 
	{				
		try {	
			$model = $this->getModel($id, __FUNCTION__);		
			$model->delete();
			return $this->prepareResponse(204, sprintf('Model with ID: %s has been deleted successfully.', $id));
		}
		catch(ModelNotFoundException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch(AuthorizationException $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
		catch (Exception $e) {
			return $this->prepareResponse($e->getCode(), $e->getMessage());
		}
	}

	/*
	* Deletes or destroys the specified model
	* @input $id model id
	* @return null
	*/
	public function destroy($id) 
	{
		return $this->delete($id);
	}

	/*
	* CORS handler
	*
	*/	
	public function corsHandler()
	{
		return $this->prepareResponse(200, 'OK');
	}

	// INTERNAL HELPERS METHODS

	protected function getModel($id, $caller = 0) {
		$permissions = $this->hasAccess($caller);
		$model = $this->_model::where('id', $id);

		if(!is_object($model->first()))
			throw new ModelNotFoundException("Model NOT found", 404);
			
		$model = $this->applyAuthorizationFilter($model, $permissions);

		if (!is_object($model->first()))
			throw new AuthorizationException('Access denied', 401);

		$model = $model->first();
		
		return $model;
	}

	protected function applyAuthorizationFilter($query, $permissions)
	{
		switch ($permissions->enabled) {
			case 1:
				if($permissions->owner == 1) {
					$user = JWTAuth::parseToken()->toUser();
					$query = $query->where('user_id', $user->id);
				}
				elseif ($permissions->everyone == 1) {
					// Do nothing
				}
				elseif ($permissions->owner == 0 && $permissions->everyone == 0) {
					$query->where('id',0);
				}
				break;
			
			default:
				// Do nothing
				break;
		}
		return $query;
	}

	

	protected function getOrderByString($queryString)
	{
		$orderByString = '';
		$fields = explode(';', $queryString);
		foreach ($fields as $field) {
			list($column, $direction) = explode(':', $field);
			$orderByString .= "`$column` $direction, ";
		}
		$orderByString = substr($orderByString, 0, -2);

		return $orderByString;
	}

	protected static function prepareResponse($code, $message, $data = [], $paginate = false)
	{
		$response = [
			'statusCode'  => $code ?: 500,
			'message'		=> $message ?: 'error',
			'data'			=> $data ?: null	
		];

		return $paginate ? $data : response()->json($response, $response['statusCode']);
	} 
	
	/*
	* Authorize user action on the current model
	* @return true if user user has access, otherwise throw AuthorizationException
	*/
	protected function authorizeUserAction()
	{
		// $apiConfig = $this->getAPIConfig();

		// if(!$apiConfig['auth_required']) {
		// 	return true;
		// }

		// $user = JWTAuth::parseToken()->toUser();

		// if($user->hasUserPermission($apiConfig['permissions'])) {
		// 	return true;
		// } 
			
		// throw new AuthorizationException("Access denied", 401);
	}

	protected function hasAccess($action) 
	{		
		$permissions = json_decode($this->permissions['config']);
		$permissions = array_filter($permissions, function($permission) use ($action) {
			return $permission->type == $action;
		});

		$permissions = array_first($permissions);
		$permissions->enabled = $this->permissions['enabled'];

		return $permissions;
	}

	protected function unauthorisedAction() 
	{
		return $this->prepareResponse(403, 'Action is not permitted.', null);
	}

	protected function getAPIConfig()
	{
		$path = array_first(explode('\Models', $this->_model));
		$path = str_replace('\\', '/', strtolower($path));

		$data = [];

		$this->filePath = 'plugins/'.$path.'/api.xaml';

		$filePath = File::symbolizePath($this->filePath);

		if(File::isFile($filePath)){
			$data = Yaml::parseFile($filePath);			
		}

		return array_first($data['apis']);
	}

	protected function mapOperator($operator) {
		switch ($operator) {
			case 'is':
				return '=';
				break;
			case 'not':
				return '!=';
				break;
			case 'lt':
				return '<';
				break;
			case 'gt':
				return '>';
				break;
			case 'lte':
				return '<=';
				break;
			case 'gte':
				return '>=';
				break;
			case 'in':
				return 'IN';
				break;
			case '!in':
				return 'NOT IN';
				break;
			case 'like':
				return 'LIKE';
				break;			
			default:
				return '=';
				break;
		}
	}
}