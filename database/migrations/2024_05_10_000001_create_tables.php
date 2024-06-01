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
        Schema::create('Account', function (Blueprint $table) {
            $table->id();
            $table->string('userName');
            $table->string('password');
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('AccountSession', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accountId');
            $table->timestamps();

            $table->foreign('accountId')->references('id')->on('Account')->onDelete('cascade');
        });

        Schema::create('User', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('gender');
            $table->string('address');
            $table->unsignedBigInteger('accountId');
            $table->timestamps();

            $table->foreign('accountId')->references('id')->on('Account')->onDelete('cascade');
        });

        Schema::create('Department', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('Teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('departmentId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('User')->onDelete('cascade');
            $table->foreign('departmentId')->references('id')->on('Department')->onDelete('cascade');
        });

        Schema::create('Student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('departmentId');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('User')->onDelete('cascade');
            $table->foreign('departmentId')->references('id')->on('Department')->onDelete('cascade');
        });

        Schema::create('Class', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('teacherId');
            $table->string('courseName');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->string('status');
            $table->integer('numberOfSession');
            $table->unsignedBigInteger('departmentId');
            $table->timestamps();

            $table->foreign('teacherId')->references('id')->on('Teacher')->onDelete('cascade');
            $table->foreign('departmentId')->references('id')->on('Department')->onDelete('cascade');
        });

        Schema::create('ClassRegistration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('classId');
            $table->timestamps();

            $table->foreign('studentId')->references('id')->on('Student')->onDelete('cascade');
            $table->foreign('classId')->references('id')->on('Class')->onDelete('cascade');
        });

        Schema::create('ClassStudent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('classId');
            $table->decimal('score', 4, 2);
            $table->timestamps();

            $table->foreign('studentId')->references('id')->on('Student')->onDelete('cascade');
            $table->foreign('classId')->references('id')->on('Class')->onDelete('cascade');
        });

        Schema::create('Session', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classId');
            $table->dateTime('sesionDate');
            $table->string('sesionLocation');
            $table->timestamps();

            $table->foreign('classId')->references('id')->on('Class')->onDelete('cascade');
        });

        Schema::create('Attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('studentId');
            $table->unsignedBigInteger('sessionId');
            $table->timestamps();

            $table->foreign('studentId')->references('id')->on('Student')->onDelete('cascade');
            $table->foreign('sessionId')->references('id')->on('Session')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Account');
        Schema::dropIfExists('AccountSession');
        Schema::dropIfExists('User');
        Schema::dropIfExists('Department');
        Schema::dropIfExists('Teacher');
        Schema::dropIfExists('Student');
        Schema::dropIfExists('Class');
        Schema::dropIfExists('ClassRegistration');
        Schema::dropIfExists('ClassStudent');
        Schema::dropIfExists('Session');
        Schema::dropIfExists('Attendance');
    }
};
