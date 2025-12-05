<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_token_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_token_id')->constrained('account_tokens')->onDelete('cascade');
            $table->string('name');   // token, api_key, login, password 
            $table->text('value');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_token_fields');
    }
};
