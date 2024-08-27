<?php namespace AhmadFatoni\ApiGenerator\Controllers\api;

use Cms\Classes\Controller;

use ReflectionClass;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Models\Status;
use AhmadFatoni\ApiGenerator\Models\Profile;
use AhmadFatoni\ApiGenerator\Models\Signature;
use AhmadFatoni\ApiGenerator\Models\ApiGenerator;

use AhmadFatoni\ApiGenerator\Models\PetitionDocuments;

use Exception;
use Event;
use Rainlab\User\Models\User;
use System\Classes\PluginManager;

class SignatureController extends Controller {

	use \AhmadFatoni\ApiGenerator\traits\StandardAPIController;

	public $_model = 'AhmadFatoni\ApiGenerator\Models\Signature';
	

	public $collection = 'AhmadFatoni\ApiGenerator\Resources\Signature';
	public $_model_petition = 'AhmadFatoni\ApiGenerator\Models\ApiGenerator';



protected $profile_details;

	/*public $permissions = [
		'enabled' => 0,
		'config' => '[{"id":"-1"}]'
		
	];*/
	public $permissions = [
		'enabled' => 1,
		'config' => '[{"type":"index","everyone":"1","owner":"","id":"-1"},{"type":"show","everyone":"1","owner":"","id":"-4"},{"type":"create","everyone":"1","owner":"","id":"-7"},{"type":"update","everyone":"","owner":"1","id":"-10"},{"type":"delete","everyone":"","owner":"1","id":"-13"}]'
		];	

	public function __contruct()
	{
		parent::__construct();	
	}

	public function hasSigned(Request $request)
	{
		$model = new $this->$_model_petition;
		
		
		 return $model->signatures()->where('email', $request->$email)->exists();
	}

	

	public function update(Request $request,$id)
    {

   $petition =ApiGenerator::findOrFail($id);
 
   try {


    $existingSignature = Signature::where('petition_id', $id)
                                   ->where('email', $request->email)
                                   ->first();


								 			   

   if ( !$existingSignature ) {
	
   $signature = new Signature([
	'petition_id' =>$request->id,
    'name' => $request->name,
		'surname' => $request->surname,
		'email' => $request->email,
		'organisation' => $request->organisation,


   ]);

   $petition->signatures()->save($signature);
  

   }else {
	
return redirect()->back()->with('error', 'You have already signed the petition.');

   }

     //retrieve the signatures associated with the petition
   $signatures = $petition->signatures;
  
   return $petition;

 
}
catch(\Exception $e) {
	\Log::info($e->getMessage());
      return null;
  }
  


} 




public function countSignatures($id)
{
    $petition = ApiGenerator::findOrFail($id);
    $count = $petition->signatures()->count();
    \Log::info('Signature count: '.$count);

    return response()->json(['count' => $count]);
}


	

}
