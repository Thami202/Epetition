<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAhmadfatoniApigeneratorPetitions extends Migration
{
    public function up()
{
    Schema::table('ahmadfatoni_apigenerator_petitions', function($table)
    {
        $table->string('comment', 255)->nullable()->change();
        $table->string('status', 255)->nullable()->change();
    });
}

public function down()
{
    Schema::table('ahmadfatoni_apigenerator_petitions', function($table)
    {
        $table->string('comment', 255)->nullable(false)->change();
        $table->string('status', 255)->nullable(false)->change();
    });
}
}
