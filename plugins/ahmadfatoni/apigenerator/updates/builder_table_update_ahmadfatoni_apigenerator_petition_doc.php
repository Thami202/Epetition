<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAhmadfatoniApigeneratorPetitionDoc extends Migration
{
    public function up()
{
    Schema::table('ahmadfatoni_apigenerator_petition_doc', function($table)
    {
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::table('ahmadfatoni_apigenerator_petition_doc', function($table)
    {
        $table->dropColumn('created_at');
        $table->dropColumn('updated_at');
        $table->dropColumn('deleted_at');
    });
}
}
