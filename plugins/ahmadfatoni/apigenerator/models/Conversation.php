<?php namespace AhmadFatoni\ApiGenerator\Models;

use Model;
use Illuminate\Support\Arr;

/**
 * Model
 */
class Conversation extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ahmadfatoni_apigenerator_conversation';

    /**
     * @var array Validation rules
     */
    public $rules = [
        // 'user_id' => 'required_unless:chatable_id, null'
    ];

    /**
     * @var array jsonable fields
     */
    public $jsonable = [
        'task'
    ];

    /**
     * @var array fillable model fields
     */
    public $fillable = [
        'phone_number',
        'name',
        'status_id',
        'task',
        'started_at',
        'ended_at',
        'chatable_type',
        'chatable_id'
    ];

    public $morphTo = [
        'chatable' => []
    ];

    public $belongsTo = [
        'status' => ['AhmadFatoni\ApiGenerator\Models\Status']
    ];

    public function beforeCreate()
    {
        $this->status_id = Status::pending()->id;
        $this->started_at = now();
    }

    public function getMediaFields()
    {
        return Arr::where($this->task['activities']['items'], function($activity, $key) {
            return $activity['type'] == 'file';
        });
    }

    public function getCurrentActivity()
    {
        $items = $this->task['activities']['items'];
        return Arr::where($items, function($activity, $key) {
            return $activity['is_current'];
        });
    }

    public function getPreviousActivity()
    {
        return 0;
    }

    public function getActivityNextValueArrayIndex($activity)
    {
        return count($activity['value']);
    }
}
