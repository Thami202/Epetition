<?php namespace AhmadFatoni\ApiGenerator\Models;

use Model;

/**
* Model
*/
class Status extends Model
{
  use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
	
	
	 /**
     * @var string The database table used by the model.
     */
    public $table = 'ahmadfatoni_apigenerator_status';
	
	/**
	*@var array Validation rules
	*/
	
	public $rules =[
	
	 'name' =>'required'
	];
	
	public static function scopeActive($query)
	{
		return $query->where('name','Active')->first();
	}
	
	 public static function scopePending($query)
	 {
		 return $query->where('name','Pending')->first();
		 
	 }

     public static function scopeComplete($query)
	 {
		 return $query->where('name','Complete')->first();
	 }

} 