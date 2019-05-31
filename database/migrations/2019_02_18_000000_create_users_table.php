<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('lastName');
            $table->string('email');
            $table->string('email_token');
            $table->string('password');
            $table->string('securityAnswer')->nullable();
            $table->string('securityQuestion')->nullable();
            $table->tinyInteger('verified')->nullable();
            $table->integer('userType')->nullable();
            $table->rememberToken();
            $table->string('api_token', 60);
            $table->integer('birthDateMonth');
            $table->integer('birthDateDay');
            $table->integer('birthDateYear');
            $table->string('originCountry', 100)->nullable();
            $table->string('actualCountry', 100)->nullable();
            $table->string('nationality')->nullable();
            $table->string('otherNationality')->nullable();
            $table->string('primaryPhone', 100);
            $table->string('secondaryPhone', 100)->nullable();
            $table->string('avatar')->nullable();
            $table->string('pathAvatar')->nullable();
            $table->string('passport', 100)->nullable();
            $table->string('identificationCard')->nullable();
            $table->string('address', 250)->nullable();
            $table->string('sector', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('profilePhoto');
            $table->unique(["email"], 'email_UNIQUE');
            $table->integer('preferred_role')->nullable();
            $table->integer('preferred_production')->nullable();
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
       Schema::dropIfExists($this->set_schema_table);
     }
}
