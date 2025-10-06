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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('issue_date');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->enum('discount_type', ['nominal', 'percent'])->default('nominal');
            $table->enum('status', ['draft', 'processing', 'completed', 'debt', 'returned', 'cancelled'])->default('draft');
            $table->enum('payment_method', ['cash', 'credit', 'transfer', 'debit', 'e_wallet'])->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('base_quantity');
            $table->decimal('buy_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->enum('discount_type', ['nominal', 'percent'])->default('nominal');
            $table->enum('status', ['normal', 'returned', 'exchanged', 'cancelled'])->default('normal');
            $table->integer('returned_quantity')->nullable()->default(0);
            $table->integer('returned_base_quantity')->nullable()->default(0);
            $table->text('return_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};