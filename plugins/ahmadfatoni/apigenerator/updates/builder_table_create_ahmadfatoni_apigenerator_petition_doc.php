<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAhmadfatoniApigeneratorPetitionDoc extends Migration
{
    public function up()
{
    Schema::create('ahmadfatoni_apigenerator_petition_doc', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('petition_id')->nullable();
        $table->string('doctype', 255)->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ahmadfatoni_apigenerator_petition_doc');
}
}
