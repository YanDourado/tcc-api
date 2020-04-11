<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCameraInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camera_info', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('camera_id')->nullable();
            $table->foreign('camera_id')
                        ->references('id')
                        ->on('cameras')
                        ->onDelete('cascade');

            $table->string('name');
            $table->string('description')->nullable();

            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();

            $table->string('cep')->nullable();
            $table->string('address')->nullable();
            $table->string('address_number')->nullable();
            
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
        Schema::dropIfExists('camera_info');
    }
}
