<?php namespace AhmadFatoni\ApiGenerator\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Status extends Controller
{
public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];


public $listConfig='config_list.yaml';
public $formConfig ='config_form.yaml';

public $requiredPermissions =[

  'Manage_Status'
];

//to be modified
 public function __construct()
{
  parent::__construct();
  BackendMenu::setContext('AhmadFatoni.ApiGenerator', 'main-menu-item', 'side-menu-status');


}


}   
