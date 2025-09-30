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
        Schema::create('pre_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->date('issue_date'); // tanggal terbit
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->date('due_date')->nullable(); // tanggal jatuh tempo
            $table->date('payment_date')->nullable(); // tanggal pembayaran
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'converted'])->default('draft');
            $table->enum('payment_method', ['cash', 'credit', 'transfer', 'debit', 'e_wallet'])->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('pre_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('base_quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_purchase_order_items');
        Schema::dropIfExists('pre_purchase_orders');
    }
};
