<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_otp_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('otp_hash');         // OTP hashé (bcrypt)
            $table->unsignedTinyInteger('attempts')->default(0); // tentatives
            $table->timestamp('expires_at');    // expiration 10 min
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otp_tokens');
    }
};
