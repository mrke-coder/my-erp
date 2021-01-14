<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar',120)->nullable();
            $table->boolean('confirmed')->default(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table){
           $table->id();
           $table->string('role',100);
           $table->string('habilities')
           $table->softDeletes();
           $table->timestamps();
        });

        Schema::create('administrators', function (Blueprint $table){
            $table->id();
            $table->string('firstName',40);
            $table->string('lastName',80)->nullable();
            $table->string('capacity')->nullable();
            $table->string('hobby')->nullable();
            $table->text('bio')->nullable();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('restrict');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_role', function (Blueprint $table){
            $table->foreignId('user_id');
            $table->foreignId('role_id');
            $table->string('clearances');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
        });

        // ======== EMPLOYEES MANAGEMENT ============
        Schema::create('departments', function (Blueprint $table){
           $table->id();
           $table->string('name');
           $table->softDeletes();
           $table->timestamps();
        });

        Schema::create('specialities', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('department_id');
            $table->foreign('department_id')->references('id')
                ->on('departments')->onUpdate('restrict')->onDelete('restrict');
             $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table){
            $table->id();
            $table->string('civility',20)->nullable()->default('M/Mme/Mlle');
            $table->string('lastName',50);
            $table->string('firstName',150);
            $table->string('cni_reference')->unique();
            $table->string('adress',60)->nullable();
            $table->string('ville',100);
            $table->string('contact');
            $table->string('email')->unique();
            $table->string('birth_day');
            $table->string('family_situation');
            $table->string('hire_date');
            $table->smallInteger('child_number')->default(0);
            $table->string('diploma');
            $table->foreignId('user_id');
            $table->foreignId('speciality_id');
            $table->foreign('speciality_id')->references('id')->on('specialities')
                ->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cvs', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('speciality',120)->nullable();
            $table->foreignId('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')
                ->onUpdate('restrict');

               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table){
            $table->id();
            $table->string('cover_letter');
            $table->string('cv');
            $table->string('contract_type',50);
            $table->string('requested_position',100);
            $table->string('request_type');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('leaves', function (Blueprint $table){
            $table->id();
            $table->date('leave_date');
            $table->date('leave_end_date')->nullable();
            $table->text('patterns');
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('trainings', function (Blueprint $table){
           $table->id();
           $table->string('type',100);
           $table->date('start_date');
           $table->date('end_date');
           $table->smallInteger('duration')->nullable();
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('departures', function (Blueprint $table){
           $table->id();
           $table->date('departure_date');
           $table->text('patterns');
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('absences', function (Blueprint $table){
            $table->id();
            $table->date('absence_date');
            $table->date('fin_absence')->nullable();
            $table->text('patterns');
            $table->boolean('justified')->default(false);
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('additional_hours', function (Blueprint $table){
           $table->id();
           $table->dateTime('start');
           $table->dateTime('end');
           $table->text('patterns');
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bonuses', function (Blueprint $table){
            $table->id();
            $table->text('patterns');
            $table->double('amount',10.2)->unsigned();
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('wages', function (Blueprint $table){
            $table->id();
            $table->double('amount',10.2)->unsigned();
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
                 $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('displacements', function (Blueprint $table){
           $table->id();
           $table->date('displacement_date');
           $table->date('return_date');
           $table->string('destination', 80);
           $table->string('means')->nullable();
           $table->double('costs',10.2);
           $table->double('accommodation_costs',10.2);
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('restrict')->onUpdate('restrict');
               $table->softDeletes();
            $table->timestamps();
        });
        // ======== END EMPLOYEES MANAGEMENT ============
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });

        Schema::table('user_role', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });

        Schema::table('administrator', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('specialities', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('employees', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('cvs', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('job_applications', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('leaves', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('trainings', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('departures', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('absences', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('additional_hours', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('bonuses', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('wages', function (Blueprint $table) {
        $table->dropSoftDeletes();
        });
        Schema::table('displacements', function (Blueprint $table) {
          $table->dropSoftDeletes();
        });
    }
}
