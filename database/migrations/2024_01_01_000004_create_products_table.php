<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained()
                  ->restrictOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description');
            $table->decimal('base_price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->string('fabric_type', 100);
            $table->string('origin', 100)->nullable();
            $table->text('care_instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_active', 'is_featured']);
            $table->index(['category_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};