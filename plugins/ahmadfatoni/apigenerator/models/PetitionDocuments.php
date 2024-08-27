<?php namespace AhmadFatoni\ApiGenerator\Models;

use Model, Log;
use RainLab\Builder\Classes\ComponentHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model
 */
class PetitionDocuments extends Model
{
    use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\SoftDelete;

	 protected $dates = ['deleted_at'];
	 use HasFactory;
    /*
     * Validation
     */
	 
	  public $rules = [
        
        'doc_type' => 'required',
		
        // 'user_id' => 'required|integer'
    ];
    /*public $rules = [
        'name'          => 'required|unique:tbl_petition,name|regex:/^[\pL\s\-]+$/u',
        'endpoint'      => 'required|unique:tbl_petition,endpoint',
        'custom_format' => 'json'
    ];*/
	
	 /**
     * @var array fillable fields
     */
    public $fillable = [
        'petition_id',
		'doc_type',
      
    ];
	
	 /**
     * @var array signatures
     */
	 //C:\xampp\htdocs\petitionproject\plugins\ahmadfatoni\apigenerator\models
	 //create a model for signatures
	 /*public $hasMany =[
	   
	   'signatures' =>['Ahmadfatoni\Apigenerator\Models\Signature','key'=>'id','otherKey'=>'petition_id']
	 
	 ];*/
	 
  
	 /**
     * @var array petitioner
     */
	 
	public $belongsTo =[
	  'user'=>['RainLab\User\Models\User'],
	  'status' =>['Ahmadfatoni\Apigenerator\Models\Status']
	
	]; 
	
	
	/**
     * @var array conversations
     */
	
	 public $morphMany =[
	   
	  'conversations'=>['Ahmadfatoni\Apigenerator\Models\Conversations','name'=>'chatable']
	 ];
	
	/**
     * @var array attachments
     */
	public $attachMany =[
	
	 'documents' =>['System\Models\File','public' =>false]
	
	];
 
	/**
	 * Creating a relationship between Attachments and Petition
	 */

     public function petition(){

        return $this->belongsTo(ApiGenerator::class);

	 }


    

	
	/*public function getStatusIdOptions()
	{
		return Status::all()->pluck('name','id')->toArray();
		
		
	}
	
	//look at this and make changes after
	public function beforeCreate()
	{
		$this->setModelStatus();
		$this->reference_no_parliament =$this->getReferenceNumber();
		$this->reference_no_public =$this->getReferenceNumber();
		
	}
	 protected function setModelStatus()
    {
        $this->status_id = isset($this->properties['status_id']) ?: Status::pending()->id;
    }

	
	 
	 protected function getModelStatus()
	 {
		 $this->status_id=isset($this->properties['status_id'])?:Status::pending()->id;
		 
	 }
	 
	 
	 protected function getReferenceNumber()
	 {
		 return strtoupper(str_random(6));
		 
	 }*/
	 
	 public function getModelForm()
	 {
		return[
		
		'name' =>'Create new'.(new ReflectionClass($this))->getShortName(),
		'status'=>'Initial',
		'item'=>[
		
		[
		 'id' =>'petition_id',
		 'type' =>'text',
		 'status' =>false,
		 'message' =>'Enter the Petition Id.',
		 'value'=>null,
		 'is_current'=>false
		 
		],
		[
		 'id' =>'doc_type',
		  'type' =>'file',
		  'mode' =>'document',
		  'status' =>false,
		  'message' =>'Upload supporting documents.',
		  'is_current' =>false,
		  'value' =>[]
		
		
		]
		
		]
		
		] ;
		 
	
		 
	 }
	 
	 
	
	 
    public $customMessages = [
        'custom_format.json' => 'Invalid Json Format Custom Condition'
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ahmadfatoni_apigenerator_petition_doc';

    /**
     * get model List
     * @return [type] [description]
     */
    public function getModelOptions(){
        
        return ComponentHelper::instance()->listGlobalModels();
    }

    /**
     * [setCustomFormatAttribute description]
     * @param [type] $value [description]
     */
    public function setCustomFormatAttribute($value){

        $json   = str_replace('\t', '', $value);
        $json   = json_decode($json);

        if( $json != null){

            if( ! isset($json->fillable) AND ! isset($json->relation) ){

                return $this->attributes['custom_format'] = 'invalid format';

            }

            if( isset($json->relation) AND $json->relation != null ){
                foreach ($json->relation as $key) {
                    if( !isset($key->name) OR $key->name == null ){
                        return $this->attributes['custom_format'] = 'invalid format';
                    }
                }
            }
        }
        
        return $this->attributes['custom_format'] = $value;
        
    }
    
}