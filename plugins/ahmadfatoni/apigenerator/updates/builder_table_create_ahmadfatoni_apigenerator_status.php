<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAhmadfatoniApigeneratorStatus extends Migration
{
    public function up()
{
    Schema::create('ahmadfatoni_apigenerator_status', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name', 10);
        $table->string('description', 255);
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ahmadfatoni_apigenerator_status');
}
}
