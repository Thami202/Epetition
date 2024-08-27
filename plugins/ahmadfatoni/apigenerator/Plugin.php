<?php namespace AhmadFatoni\ApiGenerator;

use System\Classes\PluginBase;
use October\Rain\Database\Relations\Relation;
use AhmadFatoni\ApiGenerator\Classes\MediaEventHandler;
use AhmadFatoni\ApiGenerator\Classes\Registration\BootExtensions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class Plugin extends PluginBase
{

    use BootExtensions;

    public $require = [
        'RainLab.Builder'
    ];
    

    public function registerComponents()
    {
    }
  
	
    public function registerSettings()
    {
         return [
            'settings' => [
                'label' => 'Submissions: General',
                'description' => 'Manage Submissions configurations.',
                'category' => 'Settings',
                'icon' => 'icon-cog',
                'class' => \AhmadFatoni\ApiGenerator\Models\GlobalSetting::class,
                'order' => 500,
                'keywords' => 'geography place placement'
            ]
        ];
    }

    public function boot()
    {

  
        Schema::defaultStringLength(191);
        Paginator::defaultView('pagination::bootstrap-4');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-4');
        $perPage = 10; // Change the default pagination value to 25
        Paginator::currentPageResolver(function () use ($perPage) {
            $pageName = 'page';
            $page = request()->input($pageName, 1);
            return ($page - 1) * $perPage;
        });


        $this->registerExtenstions();
		
    }
	


	
}
