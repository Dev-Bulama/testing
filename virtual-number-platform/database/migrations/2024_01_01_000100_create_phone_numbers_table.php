<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('country', 3);
            $table->json('capabilities')->nullable();
            $table->string('status')->default('available');
            $table->foreignId('rented_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rented_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('external_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_numbers');
    }
};
