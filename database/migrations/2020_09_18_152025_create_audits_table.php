<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('mime_type')->nullable();
            $table->string('file_size')->nullable();
            $table->string('source_language')->nullable();
            $table->string('destination_language')->nullable();
            $table->string('bot')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->string('ip')->nullable();
            $table->string('os')->nullable();
            $table->string('user_agent')->nullable();
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
        Schema::dropIfExists('audits');
    }
}
