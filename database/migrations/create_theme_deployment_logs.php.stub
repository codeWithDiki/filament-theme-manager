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
        Schema::create('theme_deployment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theme_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('repository');
            $table->string('branch');
            $table->string('git_username');
            $table->string('connection_type');
            $table->string('status')->default(\Codewithdiki\FilamentThemeManager\Enum\DeploymentStatusEnum::PENDING()->value);
            $table->string('commit')->nullable();
            $table->longText('meta')->nullable();
            $table->dateTime('process_end_at')->nullable();


            $table->foreign('theme_id')->references('id')->on('themes');
            $table->foreign('parent_id')->references('id')->on('theme_deployment_logs');
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
        Schema::dropIfExists('theme_deployment_logs');
    }
};
