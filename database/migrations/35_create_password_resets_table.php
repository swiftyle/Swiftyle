<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('phone', 255)->nullable()->index();
            $table->string('email', 255)->nullable()->index();
            $table->string('otp_code', 255)->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->string('email_token', 6)->nullable();
            $table->timestamp('email_token_expiry')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['phone', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
};

