<?php namespace AhmadFatoni\ApiGenerator\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Conversations extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController'    ];
    
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'Manage_Conversations' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('AhmadFatoni.ApiGenerator', 'main-menu-item', 'side-menu-conversation');
    }
}
