<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAhmadfatoniApigeneratorSignature2 extends Migration
{
    public function up()
    {
        Schema::table('ahmadfatoni_apigenerator_signature', function($table)
        {
            $table->integer('petition_id')->unsigned()->change();
        });
    }
    
    public function down()
    {
        Schema::table('ahmadfatoni_apigenerator_signature', function($table)
        {
            $table->integer('petition_id')->unsigned(false)->change();
        });
    }
}
