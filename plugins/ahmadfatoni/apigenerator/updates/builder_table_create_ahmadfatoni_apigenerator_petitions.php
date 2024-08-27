<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAhmadfatoniApigeneratorPetitions extends Migration
{
    public function up()
{
    Schema::create('ahmadfatoni_apigenerator_petitions', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('title', 255);
        $table->text('description');
        $table->integer('status_id');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->integer('user_id');
        $table->string('reference_no_parliament', 255);
        $table->string('reference_no_public', 255);
        $table->string('intended_to', 255);
        $table->string('comment', 255);
        $table->string('status', 255);
        $table->dateTime('receive_date')->nullable();
        $table->dateTime('feedback_date')->nullable();
        $table->dateTime('petition_date')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('ahmadfatoni_apigenerator_petitions');
}
}
