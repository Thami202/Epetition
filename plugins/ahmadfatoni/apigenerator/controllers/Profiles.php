<?php namespace AhmadFatoni\ApiGenerator\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Profiles extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'Manage_profile' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.User', 'user', 'user_profiles');
    }    
}
