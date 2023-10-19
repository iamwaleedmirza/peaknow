<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibertyPatientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liberty_patient_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('PatientId')->nullable();
            $table->string('ExternalId')->nullable();
            $table->bigInteger('AccountNumber')->nullable();
            $table->string('ChargeCode')->nullable();
            $table->string('FirstName');
            $table->string('MiddleInitial')->nullable();
            $table->string('LastName');
            $table->date('BirthDate');
            $table->string('Street1')->nullable();
            $table->string('Street2')->nullable();
            $table->string('City')->nullable();
            $table->string('State')->nullable();
            $table->string('Zip')->nullable();
            $table->string('Gender')->nullable();
            $table->string('SSN')->nullable();
            $table->string('DriversLicenseNumber')->nullable();
            $table->string('Phone')->nullable();
            $table->string('PhoneType')->nullable();
            $table->string('Phone2')->nullable();
            $table->string('Phone2Type')->nullable();
            $table->string('Email')->nullable();
            $table->string('Language')->nullable();
            $table->string('CustomField1')->nullable();
            $table->string('CustomField2')->nullable();
            $table->string('CustomField3')->nullable();
            $table->string('CustomField4')->nullable();
            $table->text('Allergies')->nullable();
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
        Schema::dropIfExists('liberty_patient_details');
    }
}
