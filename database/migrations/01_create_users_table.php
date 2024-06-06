<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('username', 255)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->timestamp('email_verified_at')->useCurrent();
            $table->string('password', 255)->nullable();
            $table->string('phone_number', 20)->unique()->nullable();
            $table->enum('phone_verified',['Yes', 'No'])->default('No');
            $table->enum('gender',['Male','Female','Other'])->default('Other');
            $table->enum('role', ['Admin','Costumer','Seller'])->default('Costumer');
            $table->string('avatar')->default('http://localhost:8000/assets/images/dashboard/1.png');
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider_token')->nullable();
            $table->rememberToken();
            // $table->timestamps();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
