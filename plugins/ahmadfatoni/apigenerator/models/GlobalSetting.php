<?php namespace AhmadFatoni\ApiGenerator\Models;

use model;

class GlobalSetting extends model {
	 /**
     * @var array implement these behaviors
     */
	public $implement = [
        \System\Behaviors\SettingsModel::class
    ];

    /**
     * @var string settingsCode unique to this model
     */
    public $settingsCode = 'parliament_submissions_settings';

    /**
     * @var string settingsFields configuration
     */
    public $settingsFields = 'fields.yaml';

}