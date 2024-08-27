<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAhmadfatoniApigeneratorSignature extends Migration
{
    public function up()
{
    Schema::table('ahmadfatoni_apigenerator_signature', function($table)
    {
        $table->string('organisation', 255);
    });
}

public function down()
{
    Schema::table('ahmadfatoni_apigenerator_signature', function($table)
    {
        $table->dropColumn('organisation');
    });
}
}
