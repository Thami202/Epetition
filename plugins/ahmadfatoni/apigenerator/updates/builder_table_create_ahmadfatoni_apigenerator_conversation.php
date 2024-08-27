<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAhmadfatoniApigeneratorConversation extends Migration
{
    public function up()
{
    Schema::create('ahmadfatoni_apigenerator_conversation', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('phone_number', 255);
        $table->string('name', 255);
        $table->dateTime('started_at')->nullable();
        $table->dateTime('ended_at')->nullable();
        $table->integer('user_id')->nullable();
        $table->text('task');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->integer('chatable_id')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ahmadfatoni_apigenerator_conversation');
}
}
