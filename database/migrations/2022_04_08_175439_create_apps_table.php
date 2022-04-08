<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('sp_id');
            $table->string('app_name');
            $table->string('sp_user_id');
            $table->string('domains');
            $table->string('sp_server_id');
            $table->string('runtime');
            $table->integer('status');
            $table->string('latest_screenshot');
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
        Schema::dropIfExists('apps');
    }
}
