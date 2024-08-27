<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAhmadfatoniApigeneratorProfile extends Migration
{
    public function up()
{
    Schema::create('ahmadfatoni_apigenerator_profile', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('id_number');
        $table->string('phone_number');
        $table->string('province');
        $table->string('municipality');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ahmadfatoni_apigenerator_profile');
}
}
