<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ajouter le numéro de suivi (déjà présent)
            $table->string('tracking_number')->nullable()->after('status');

            $table->string('shipping_carrier')->nullable()->after('tracking_number');
            $table->string('email')->nullable()->after('user_id'); // Pour les commandes invités
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'shipping_carrier', 'email']);
        });
    }
};
