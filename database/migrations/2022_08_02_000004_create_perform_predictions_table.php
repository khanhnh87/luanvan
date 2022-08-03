<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformPredictionsTable extends Migration
{
    public function up()
    {
        Schema::create('perform_predictions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ten')->nullable();
            $table->string('hinh_anh');
            $table->string('kqdd');
            $table->string('kqtt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
