<?php namespace AhmadFatoni\ApiGenerator\Controllers\api;

use Input;
use Event;
use ReflectionClass;
use Cms\Classes\Controller;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Rainlab\User\Models\User;
use System\Classes\PluginManager;
use AhmadFatoni\ApiGenerator\Models\Status;
use AhmadFatoni\ApiGenerator\Models\Profile;
use AhmadFatoni\ApiGenerator\Models\PetitionDocuments;
use AhmadFatoni\ApiGenerator\Models\PetitionCreated;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
//use Illuminate\Support\Facades\Auth;



//use AhmadFatoni\ApiGenerator\Models\Conversation;
use Auth;
use Http;

class PetitionController extends Controller{
	
use \AhmadFatoni\ApiGenerator\traits\StandardAPIController;

public $_model = 'AhmadFatoni\ApiGenerator\Models\ApiGenerator';
protected $_model_profile = 'AhmadFatoni\ApiGenerator\Models\Profile';
public $_model_user ='Rainlab\User\Models\User';
public $collection = 'AhmadFatoni\ApiGenerator\Resources\Petition';
public $_documents ='AhmadFatoni\ApiGenerator\Models\PetitionDocuments';
public $_signature = 'AhmadFatoni\ApiGenerator\Models\Signature';
protected $profile_details;



public $permissions = [
'enabled' => 1,
'config' => '[{"type":"index","everyone":"1","owner":"","id":"-1"},{"type":"show","everyone":"1","owner":"","id":"-4"},{"type":"create","everyone":"1","owner":"","id":"-7"},{"type":"update","everyone":"","owner":"1","id":"-10"},{"type":"delete","everyone":"","owner":"1","id":"-13"}]'
];	
	
	
public function __contruct()
{
		parent::__construct();	
}

public function getPetitionsPendingSignatures(Request $request)
{
 $model =new $this->_model;
return $this->prepareResponse(200,'success',$model->getModelForm()); 
	
}

public function getPetitionForm(Request $request)
{
	
	$model = new $this->_model;
    $conversation =$this->getOrCreateConversation($model,$request);	
	
}

public function startConversation(Request $request)
{
$model = new $this->_model;
$conversation = $this->getOrCreateConversation($model, $request);
return [
'id' => $conversation->id,
'title' => $conversation->task['name'],
'status' => $conversation->status,
'started_at' => $conversation->started_at,
'ended_at' => $conversation->ended_at,
'phone_number' => $conversation->phone_number,
'task' => $this->getConversationCurrentActivity($conversation)
];

}

protected function getConversationCurrentActivity($conversation)
{
$items = $conversation->task['activities']['items'];

return Arr::flatten(Arr::where($items, function($activity, $key) {
return $activity['is_current'];
}));
}

protected function getOrCreateConversation($model, $request)
	{

		if($this->hasPendingConversation($model)) 
			return $model->conversations->first();

		$model->conversations()->add(new Conversation([
			'name' => $request->name,
			'phone_number' => $request->phone_number,
			'task' => [
				'name' => 'Add new ' . (new ReflectionClass($model))->getShortName(),
				'status' => 0,
				'activities' => $model->getModelForm()
			]
		]));

		return $model->conversations->first();
	}

protected function hasPendingConversation($model)
{
return $model->conversations()->where('status', 0)->count() ? true : false;
}

public function retrievePetition(Request $request, $id)
{
    try {
        // Assuming $this->_model is an instance of your Petition model
        $model = $this->_model::with(['user', 'user.profile'])->findOrFail($id);

        // Optionally, you can also load other relationships
        // $model = $model->load('status', 'signatures');

        return response()->json(['petition' => $model], 200);
    } catch (\Exception $e) {
        // Handle exceptions
        return response()->json(['message' => 'Error retrieving petition.', 'error' => $e->getMessage()], 500);
    }
}



/*public function sign(Request $request,$id)
{
	$model = new $this->_model;
$model = $model::findOrFail($id);
$validated = $request->validated();
if (!$model->hasSigned($validated['email'])) {
$model->signatures()->add(new Signgature([
'name' => $request->name,
'surname' => $request->surname,
'email' => $request->email,
'organisation' => $request->organisation,

]));
$model->save();
}
\Log::info($request->name);
	\Log::info($request->email);
	\Log::info($request->surname);
	\Log::info($request->organisation);



        
      return $model;


}
*/
public function destroy($id)
{
	$model = ApiGenerator::findOrFail($id);
    
        $model->delete();

        return response()->json(['message' => 'Petition deleted successfully']);
	
}	


public function show(Request $request, $id)
    {
        // Retrieve the petition details based on the given id
        $petition = $this->_model::findOrFail($id);

        // If the petition doesn't exist, return a 404 response
        if (!$petition) {
            abort(404);
        }

        // Pass the petition details to the view
        return response()->json($petition);
    }


/*
**
*search for petition
*/

public function search(Request $request)
{
	
    $searchTerm = $request->searchTerm;
	
	$petitions = $this->_model ::where('title','like', "%{$searchTerm}%")
	                       ->orWhere('reference_no_public', 'like', "%{$searchTerm}%")
	                             ->where('status_id','1') ->get();

	return response()->json([
		'data' => $petitions
	]);
	
	

}
/*searchclosed*/
public function searchClosed(Request $request)
{
	
    $searchTerm = $request->searchTerm;
	
	$petitions = $this->_model ::where('title','reference_no_public', 'like', "%{$searchTerm}%")
	                            ->orWhere('reference_no_public', 'like', "%{$searchTerm}%")
	                             ->where('status_id','4') ->get();

	return response()->json([
		'data' => $petitions
	]);
	
	

}


/*
**
*count number of petitions
*/

public function countPetition(Request $request)
{
	$count = $this->_model::where('id', '1')->count();

	return response()->json(['count' => $count]);
}


protected function getOrCreateUser(Request $request)
{
 // for user
  try {
    $user = $this->_model_user::where('email', $request->email)->first();
    if($user) {
      return $user;
    }
    else {
      // Create new user
      $user = new $this->_model_user;
      $user->fill($request->all());
      $user->save();
    
      return $user;
    }
  }
  catch(\Exception $e) {
	\Log::info($e->getMessage());
      return null;
  }

}



public function store(Request $request)
{
	  
  // checking if user signed in
  \Log::info('Request data: ' . json_encode($request->all()));
   
  try {
    $user = $this->getOrCreateUser($request); 	
    
    if(!$user)
      throw new \Exception('Petition cannot be created. Something went wrong.', 404);
 
  	// for petition
  	$model = new $this->_model;
  	$model->fill([
  		'user_id' =>$user->id,
  		'title' => $request->title,
  		'intended_to' => $request->intended_to,
  		'description' => $request->description,
  		//'reason' => $request->reason,
  	]);
	 
  	$model->save();
	  $reference_number = $model->reference_no_public;
        
	  // Update reference number in the model in case there was any issue while saving
	  if(empty($reference_number)) {
		  $model->reference_no_parliament = $model->getReferenceNumber();
		  $model->reference_no_public = $model->getReferenceNumber();
		  $model->save();
		  $reference_number = $model->reference_no_public;
	  }
    \Log::info(json_encode($request->get('file')));
  
    $result = Http::get($request->get('file')['url'], function($http) {
      
    });
    
    if($result->code == 200) {
    
    	$file = (new \System\Models\File)->fromData($result->body, $request->get('file')['name']);
    	$file->is_public = false;
    	$file->save();
    
    	$model->documents()->add($file);
    } 

	  return response()->json($model, 200);
  }

  

  

  catch(\Exception $e) {
    return [
      'status' => 'failed',
      'message' => $e->getMessage(),
      'data' => null,
      'code' => $e->getCode()
    ];
  }
	
}	

/**
*@retrieve Petition attachment
*@var $request Request
*@var id int Petitition id
*/
public function retrieveAttachment(Request $request, $id)
{
    try {
        $model = $this->_model::with('user')->findOrFail($id);

        // Check if the petition has any attachments
        $attachments = $model->documents;

        if ($attachments->isEmpty()) {
            // Return the model without any attachments
            return $model;
        }

        // Attach the attachments data to the model
        $model->attachments = $attachments;

        // Return the model with attachments data
        return $model;
    } catch (\Exception $e) {
        // Handle exceptions
        return response()->json(['message' => 'Error retrieving attachments.', 'error' => $e->getMessage()], 500);
    }
}


 /*public function retrieveAttachment(Request $request, $id)
 {
        try {
            $model = $this->_model::findOrFail($id);

            // Check if the petition has any attachments
            $attachments = $model->documents;

            if ($attachments->isEmpty()) {
                return response()->json(['message' => 'No attachments found for the petition.'], 404);
            }

            // Return the attachments data
            return response()->json($attachments, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['message' => 'Error retrieving attachments.', 'error' => $e->getMessage()], 500);
        }
}*/





/**
 * Add Petition attachment
 * @var $request Request
 * @var id int Petition Id
 */

 public function addAttachment(Request $request, $id)
 {
	try {
		$model = $this->_model::findorfail($id);

		\Log::info('HAS File'.$request->hasFile('attachments'));
	
		// $file = (new \System\Models\File)->fromData($response->body(), $filename);
		$model->documents()->create([
			'data' => $request->file('attachments')
		]);

		return $model;
	} catch (\Exception $e) {
		//throw $th;
		return $e;
	}
 }

/*public function index()
 {
	$model = new $this->_model;
	 $petitions = $model::paginate(10);
	 return $petitions ;
 }*/


public function extendedprofile()
{
	return $this->hasOne['profile'] = Profile::class;
    
}


public function countOpenPetitions()
{
	$model = new $this->_model;

	
	$petitions = $model::where('status_id', '1')->get();

$count = $petitions->count();

\Log::info('Petition count: '.$count);

return response()->json(['count' => $count]);
}




public function countClosedPetitions()
{
	$model = new $this->_model;

	
	$petitions = $model::where('status_id', '4')->get();

$count = $petitions->count();

\Log::info('Petition count: '.$count);

return response()->json(['count' => $count]);
}



public function countPetitions(Request $request, $id)
{
	$modeluser = $this->_model_user::findorfail($id);
	$model = new $this->_model;

    //$userId = Auth::id(); // retrieve the currently authenticated user ID
    $count = $model::where('user_id', $modeluser->id)->count();

    \Log::info('Petition count for user '  . $count);

    return response()->json(['count' => $count]);
}

public function getTotalNumberOfPetitionsPerWeek($created_at)
{
    $model = $this->_model::where('created_at', '>=', $created_at)
        ->where('created_at', '<', date('Y-m-d', strtotime($created_at . ' + 7 days')))
        ->selectRaw('WEEK(created_at) AS week, COUNT(*) AS total_petitions')
        ->groupBy('week')
        ->get();

   // $count = $model->count();

   // \Log::info('Petitions per week: ' . $count);
	return response()->json(['count' => $model->toArray()]);

}




public function update(Request $request,$id)
{
	\Log::info('Request data: ' . json_encode($request->all()));

	try {
	$model = new $this->_model;
	$model = $model::findOrFail($id);
	
	$model->fill([
		'user_id' =>$request->id,
		'title' => $request->title,
		'intended_to' => $request->intended_to,
		'description' => $request->description,
		]);
		\Log::info($request->title);
		\Log::info($request->description);
		\Log::info($request->intended_to);
	
	$model->save();


	
	\Log::info('Request Picture: '.json_encode($request->get('file')));
	\Log::info('File URL: ' . $request->get('file')['url']);

    $result = Http::get($request->get('file')['url'], function($http) {
      
    });
    \Log::info('HTTP GET response: ' . $result->body);

    if($result->code == 200) {
    
    	$file = (new \System\Models\File)->fromData($result->body, $request->get('file')['name']);
    	$file->is_public = false;
    	$file->save();
    
    	$model->documents()->add($file);
    } 
	\Log::info('File data: ' . $result->body);
	  return response()->json($model, 200);
  }catch(\Exception $e) {
    return [
      'status' => 'failed',
      'message' => $e->getMessage(),
      'data' => null,
      'code' => $e->getCode()
    ];
  }


	


}

public function reason(Request $request,$id)
{

$model = new $this->_model;
	$model = $model::findOrFail($id);
	
	$model->fill([
		'user_id' =>$request->id,
		'title' => $request->title,
		'intended_to' => $request->intended_to,
		'description' => $request->description,
		 'reason' => $request->reason,
		]);
		\Log::info($request->title);
		\Log::info($request->description);
		\Log::info($request->reason);
	
	$model->save();

	return $model;

}


public function actionRequired(Request $request,$id)
{
	

	try {
$model = new $this->_model;
	$model = $model::findOrFail($id);
	
	$model->fill([
		'user_id' =>$request->id,
		'title' => $request->title,
		'intended_to' => $request->intended_to,
		'description' => $request->description,
		'checked' => $request->checked,
		]);
		/*\Log::info($request->title);
		\Log::info($request->description);
		\Log::info($request->reason);*/
	
	$model->save();


	\Log::info(json_encode($request->get('file')));
  
    $result = Http::get($request->get('file')['url'], function($http) {
      
    });
    
    if($result->code == 200) {
    
    	$file = (new \System\Models\File)->fromData($result->body, $request->get('file')['name']);
    	$file->is_public = false;
    	$file->save();
    
    	$model->documents()->add($file);
    } 

	  return response()->json($model, 200);
  }catch(\Exception $e) {
    return [
      'status' => 'failed',
      'message' => $e->getMessage(),
      'data' => null,
      'code' => $e->getCode()
    ];
  }


  \Log::info('Request data: ' . json_encode($request->all()));

}

public function showComments($id)
{
	try {
	$model = new $this->_model;
	$model = $model::findOrFail($id);
	$count = $model::where('id', $id)->whereNotNull('comment')->count();
	\Log::info('Comment count: '.$count); 
	return response()->json(['count' => $count]);
	
	
	
	}catch(Exception $e)
	{
		return response()->json(['error' => $e->getMessage()]);
	}

}

public function countActivePetitions()
{
	$model = new $this->_model;

	
	$petitions = $model::where('status_id', '3')->get();

$count = $petitions->count();

\Log::info('Petition count: '.$count);

return response()->json(['count' => $count]);
}

public function showWithdraw($id)
{
	try {
	$model = new $this->_model;
	$model = $model::findOrFail($id);
	$count = $model::where('id', $id)->whereNotNull('reason')->count();
	\Log::info('withdraw count: '.$count); 
	return response()->json(['count' => $count]);
	
	}catch(Exception $e)
	{
		return response()->json(['error' => $e->getMessage()]);
	}

}

}