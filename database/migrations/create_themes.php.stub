<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('directory');
            $table->string('vendor');
            $table->string('git_username');
            $table->string('git_repository');
            $table->string('git_branch');
            $table->string('connection_type');
            $table->string('git_provider');
            $table->boolean('is_child')->default(false);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->longText('meta')->nullable();


            $table->foreign('parent_id')->references('id')->on('themes');
            $table->softDeletes();
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
        Schema::dropIfExists('themes');
    }
};
