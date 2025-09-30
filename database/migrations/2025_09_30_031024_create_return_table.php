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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique(); // RTN-2025-001
            $table->date('return_date');
            $table->enum('return_type', ['sales_return', 'purchase_return']); // Jenis retur
            // $table->unsignedBigInteger('original_order_id'); // ID order asli
            $table->morphs('original_item');
            $table->string('original_order_type'); // 'sales_order' atau 'purchase_order'
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('restrict');
            $table->decimal('total_return_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'processed'])->default('draft');
            $table->text('return_reason')->nullable(); // Alasan umum retur
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('approved_date')->nullable();
            $table->timestamps();
        });

        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained()->onDelete('cascade');
            // $table->unsignedBigInteger('original_item_id'); // ID dari sales_order_items atau purchase_order_items
            $table->morphs('original_item');
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->foreignId('unit_id')->constrained()->onDelete('restrict');
            $table->integer('return_quantity');
            $table->integer('return_base_quantity');
            $table->decimal('original_price', 15, 2);
            $table->decimal('return_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->enum('condition', ['good', 'damaged', 'expired', 'defective'])->default('good');
            $table->text('item_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
    }
};
