<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('base');
            $table->string('namespace');
            $table->string('table');
            $table->string('prefix');
            $table->boolean('documentation');
            $table->boolean('migration');
            $table->boolean('run_migration');
            $table->boolean('route');
            $table->string('middlewares');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('api_resources');
    }
}
