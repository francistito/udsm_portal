<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('race_registrations', function (Blueprint $table) {
            $table->id();
            $table->text('first_name');
            $table->text('last_name');
            $table->text('email');
            $table->text('phone_number');
            $table->text('nationality')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth');
            $table->smallInteger('race_type_cv_id');
            $table->smallInteger('gender_cv_id');
            $table->text('team_name')->nullable();
            $table->smallInteger('race_category_cv_id')->nullable();
            $table->smallInteger('tshirt_type_cv_id')->nullable();
            $table->smallInteger('tshirt_size_cv_id')->nullable();
            $table->smallInteger('five_km')->nullable()->comment('no of runner for 5kms if is a group');
            $table->smallInteger('ten_km')->nullable()->comment('no of runner for 10km if is a group');
            $table->smallInteger('twenty_one_km')->nullable()->comment('no of runner for 21kms if is a group');
            $table->smallInteger('xxl')->nullable();
            $table->smallInteger('xl')->nullable();
            $table->smallInteger('l')->nullable();
            $table->smallInteger('m')->nullable();
            $table->smallInteger('s')->nullable();
            $table->smallInteger('xs')->nullable();
            $table->smallInteger('terms')->default(1);
            $table->smallInteger('race_cate');
            $table->smallInteger('ispaid')->default(0);
            $table->smallInteger('iscancel')->default(0);
            $table->string('uuid');
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
        Schema::dropIfExists('race_registrations');
    }
}
