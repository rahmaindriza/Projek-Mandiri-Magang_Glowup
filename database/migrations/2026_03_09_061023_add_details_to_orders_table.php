<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambah kolom alamat detail (Shopee Style)
            $table->string('province')->after('phone')->nullable();
            $table->string('city')->after('province')->nullable();
            $table->string('district')->after('city')->nullable();
            $table->string('village')->after('district')->nullable();
            $table->string('postal_code')->after('village')->nullable();

            // Menambah kolom metode pembayaran & resi
            $table->string('payment_method')->after('total_price')->nullable(); // cod / transfer
            $table->string('tracking_number')->after('payment_method')->nullable(); // nomor resi
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['province', 'city', 'district', 'village', 'postal_code', 'payment_method', 'tracking_number']);
        });
    }
};
