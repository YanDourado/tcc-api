<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('camera_id')->nullable();
            $table->foreign('camera_id')
                        ->references('id')
                        ->on('cameras')
                        ->onDelete('cascade');

            $table->timestamp('viewed_at')->nullable();
            $table->unsignedBigInteger('viewed_by')->nullable();
            $table->foreign('viewed_by')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');

            $table->boolean('has_human')->default(false);
            $table->string('image_url');

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
        Schema::dropIfExists('alerts');
    }
}
