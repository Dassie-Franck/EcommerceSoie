<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ajouter la colonne reference si elle n'existe pas
            if (!Schema::hasColumn('orders', 'reference')) {
                $table->string('reference')->unique()->after('id');
            }

            // Ajouter la colonne email si elle n'existe pas
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->nullable()->after('user_id');
            }

            // Ajouter les colonnes de suivi si elles n'existent pas
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('status');
            }

            if (!Schema::hasColumn('orders', 'shipping_carrier')) {
                $table->string('shipping_carrier')->nullable()->after('tracking_number');
            }

            // Ajouter les autres colonnes nécessaires
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('shipping_carrier');
            }

            if (!Schema::hasColumn('orders', 'shipping_fee')) {
                $table->decimal('shipping_fee', 10, 2)->default(0)->after('subtotal');
            }

            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('shipping_fee');
            }

            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('total');
            }

            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('address');
            }

            if (!Schema::hasColumn('orders', 'country')) {
                $table->string('country')->nullable()->after('city');
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('country');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'reference',
                'email',
                'tracking_number',
                'shipping_carrier',
                'subtotal',
                'shipping_fee',
                'total',
                'phone',
                'address',
                'city',
                'country',
                'payment_method',
                'payment_status',
                'notes'
            ]);
        });
    }
};
