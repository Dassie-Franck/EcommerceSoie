<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->json('countries')->nullable();
    $table->decimal('price', 8, 2)->default(0.00);
    $table->decimal('free_above', 10, 2)->nullable();

    // CORRECTION ICI
    $table->unsignedTinyInteger('days_min');
    $table->unsignedTinyInteger('days_max');

    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
