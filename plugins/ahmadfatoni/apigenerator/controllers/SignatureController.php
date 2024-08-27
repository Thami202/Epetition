<?php namespace AhmadFatoni\ApiGenerator\Controllers;

use Backend\Classes\Controller;
use AhmadFatoni\ApiGenerator\Models\Signature;
use BackendMenu;
use Backend;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Redirect;
use Flash;


class SignatureController extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController'];
    
    public $listConfig      = 'config_list.yaml';
    public $formConfig      = 'config_form.yaml';
	public $relationConfig      = 'config_relation.yaml';
    public $reorderConfig   = 'config_reorder.yaml';
    protected $path         = "/api/";
    private $homePage       = 'ahmadfatoni/apigenerator/signaturecontroller';

    
    public $requiredPermissions = [
        'Manage_Signature'
        
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('AhmadFatoni.ApiGenerator', 'main-menu-item', 'side-menu-signature');
    }
}

