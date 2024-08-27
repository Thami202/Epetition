<?php namespace AhmadFatoni\ApiGenerator\Classes\Registration;

use Illuminate\Support\Arr;
use Event;
use Rainlab\User\Models\User;
use System\Classes\PluginManager;
use AhmadFatoni\ApiGenerator\Models\Profile;
use RainLab\User\Controllers\Users as RainLabUsersController;

trait BootExtensions {

    protected $profile_details;
   
    protected function registerExtenstions()
    {
        if(PluginManager::instance()->exists('RainLab.User')) {
            $this->extendRainLabUser();
        }
    }

    protected function extendRainLabUser() {
        User::extend(function($model) {
            $model->hasOne['profile'] = Profile::class;

            $model->addDynamicMethod('getProviceOptions', function() {
                return [
                    'City of Cape Town'
                ];
            });

            $model->addDynamicMethod('getUserIdOptions', function() use ($model) {
                return $model->get()->pluck('name', 'id')->toArray();
            });

            $model = $this->addUserFillables([
                'phone_number',
                'id_number',
                'province',
                'municipality'], $model
            );
            
            $model->bindEvent('model.beforeCreate', function() use ($model) {
                
                $this->profile_details = Arr::only($model->attributes, [
                    'phone_number',
                    'id_number',
                    'province',
                    'municipality'
                ]);

                $model->attributes = Arr::except($model->attributes, [
                    'phone_number',
                    'id_number',
                    'province',
                    'municipality'
                ]);
            });
            
            $model->bindEvent('model.afterCreate', function() use($model) {
                $model->profile()->add(new Profile([
                    'phone_number' => $this->profile_details['phone_number'],
                    'id_number' => $this->profile_details['id_number'],
                    'province' => $this->profile_details['province'],
                    'municipality' => $this->profile_details['municipality']
                ]));
            });
        });
       
        
       

        RainLabUsersController::extend(function (RainLabUsersController $users) {
            if (!isset($users->relationConfig)) {
                $users->addDynamicProperty('relationConfig');
            }
            $myConfigPath = '$/ahmadfatoni/apigenerator/controllers/profiles/config_relation.yaml';
            $users->relationConfig = $users->mergeConfig(
                $users->relationConfig,
                $myConfigPath
            );
            // Extend the Users controller with the Relation behaviour that is needed
            // to display the profile relation widget above.
            if (!$users->isClassExtendedWith('Backend.Behaviors.RelationController')) {
                $users->extendClassWith(\Backend\Behaviors\RelationController::class);
            }
        });

        // Add User Profiles menu entry to RainLab.User
        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'user_profiles' => [
                    'label'       => 'Profiles',
                    'url'         => \Backend::url('AhmadFatoni/ApiGenerator/profiles'),
                    'icon'        => 'icon-cog',
                    'permissions' => ['AhmadFatoni.ApiGenerator.Manage_profile'],
                ],
            ]);
        }, 5);


        // Add Customer Groups relation to RainLab.User form
       
    }

    /**
     * Add fillable atributes to user model
     * @param array fllable attributes
     */
    protected function addUserFillables(array $attributes, $model)
    {
        foreach($attributes as $attribute) {
            $model->addFillable($attribute);
        }    
        return $model;    
    }
}