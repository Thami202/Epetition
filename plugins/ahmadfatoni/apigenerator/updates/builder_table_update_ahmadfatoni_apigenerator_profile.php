<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAhmadfatoniApigeneratorProfile extends Migration
{
    public function up()
{
    Schema::table('ahmadfatoni_apigenerator_profile', function($table)
    {
        $table->integer('user_id');
    });
}

public function down()
{
    Schema::table('ahmadfatoni_apigenerator_profile', function($table)
    {
        $table->dropColumn('user_id');
    });
}
}
