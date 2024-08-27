<?php namespace AhmadFatoni\ApiGenerator\Models;

use Model;
use Rainlab\User\Models\User;
/**
 * Model
 */
class Profile extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ahmadfatoni_apigenerator_profile';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $fillable = [
        'phone_number',
        'id_number',
        'province',
        'municipality'
    ];

    public $belongsTo = [
        'user' => User::class
    ];

    public function getProvinceOptions() {
        return [
            'Western Cape',
        ];
    }

    public function getMunicipalityOptions() {
        return [
            'City of Cape Town',
        ];
    }

    public function getUserOptions() {
        return User::get()->pluck('name', 'id')->toArray();
    }

    public function getUserProfile()
    {
     
     return $this->hasOne(Profile::class);
 
    }
	
	
	
	 public function petitions()
    {
        return $this->hasMany('Ahmadfatoni\Apigenerator\Models\ApiGenerator','user_id');
    }

    

}
