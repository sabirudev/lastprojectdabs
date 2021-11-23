<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table');
            $table->string('vendor_id')->nullable();
            $table->string('user_id')->nullable();
            $table->text('ip_address');
            $table->text('platform');
            $table->text('browser');
            $table->text('activity');
            $table->text('method_function');
            $table->text('browser_version');
            $table->text('is_mobile');
            $table->text('is_robot');
            $table->text('is_desktop');
            $table->text('referer');
            $table->text('agent_string');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('api_log');
    }
}
