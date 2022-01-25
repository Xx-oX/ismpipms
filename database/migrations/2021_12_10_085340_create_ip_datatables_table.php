<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpDatatablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_datatables', function (Blueprint $table) {
            $table->integer('ip')->primary();
            $table->integer('gateway');
            $table->unsignedBigInteger('current_subscriber_id')->nullable();
            $table->foreign('current_subscriber_id')->references('id')->on('subscribe_datatables')->onDelete('cascade');
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
        Schema::dropIfExists('ip_datatables');
    }
}

