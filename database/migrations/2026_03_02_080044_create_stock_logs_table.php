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
    Schema::create('stock_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Menghubungkan ke tabel products
        $table->integer('qty_added');     // Jumlah stok yang ditambah
        $table->integer('stock_before');  // Stok sebelum update
        $table->integer('stock_after');   // Stok sesudah update
        $table->string('admin_name');     // Nama admin yang bertugas
        $table->text('description')->nullable(); // Catatan tambahan
        $table->timestamps();
    });

}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
