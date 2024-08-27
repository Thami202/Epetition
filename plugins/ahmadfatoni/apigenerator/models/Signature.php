<?php namespace AhmadFatoni\ApiGenerator\Models;
use AhmadFatoni\ApiGenerator\Models\ApiGenerator;
use Illuminate\Validation\Rule;

use Model,Log;

/**
 * Model
 */

use Illuminate\Database\Eloquent\Collection;

class Signature extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
	
	
	  /**
     * @var string The database table used by the model.
     */
    public $table = 'ahmadfatoni_apigenerator_signature';
	
	/**
     * @var array Validation rules
     */
    public $rules = [
        
        'name' => 'required',
        'surname' => 'required',
        'email' => 'required|email',
      
        
    ];

    //protected $rules = [];

    /* public function rules()
    {
        $petition = $this->route('petition');

        $this->rules = [
            'name' => 'required',
             'surname' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('signatures')->where(function ($query) use ($petition) {
                    return $query->where('petition_id', $petition->id);
                })
            ]
        ];

        return $this->rules;
    }*/



     /**
     * @var array fillable fields
     */
    public $fillable = [
        'petition_id',
		'name',
        'surname',
        'email'
       // 'organisation'
    ];
	
	 /**
     * @var array The model Petition
     */
    /*public $belongsTo = [
        'petition' => ['Ahmadfatoni\Apigenerator\Models\ApiGenerator', 'key' => 'id', 'otherKey' => 'petition_id']
    ];*/

    public $belongsTo = [
        'petition' => ['Ahmadfatoni\Apigenerator\Models\ApiGenerator', 'key' => 'petition_id', 'otherKey' => 'id']
    ];
 
  


    /**
     * Create a collection of signatures from a list of model instances.
     *
     * @param mixed $items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function collection($items)
    {
        if ($items instanceof Collection) {
            return $items;
        }

        return new Collection(is_array($items) ? $items : [$items]);
    }

    





 /*   public function petition()
{
    return $this->belongsTo('Ahmadfatoni\Apigenerator\Models\ApiGenerator', 'petition_id');
}*/



   /* public function petition()
    {
        return $this->belongsTo(Petition::class);
    }*/


}



