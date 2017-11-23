<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log__accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->string('method')->default("");
            $table->integer('status_code')->default(0);
            $table->string('is_json')->default("");
            $table->text('request_json')->nullable(true);
            $table->text('response_json')->nullable(true);
            $table->text('request_parameters')->nullable(true);
            $table->text('user_agent')->nullable(true);
            $table->string('request_uri')->default("");
            // $table->text('text__data')->default("");
            $table->string('ip')->default("");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log__accesses');
    }
}
