<?php namespace AhmadFatoni\ApiGenerator\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAhmadfatoniApigeneratorPetitionDoc2 extends Migration
{
    public function up()
{
    Schema::table('ahmadfatoni_apigenerator_petition_doc', function($table)
    {
        $table->renameColumn('doctype', 'doc_type');
    });
}

public function down()
{
    Schema::table('ahmadfatoni_apigenerator_petition_doc', function($table)
    {
        $table->renameColumn('doc_type', 'doctype');
    });
}
}
