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

        Schema::create('item_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->foreignId('item_group_id')->constrained('item_groups')->onDelete('set null');
            $table->foreignId('item_category_id')->constrained('item_categories')->onDelete('set null');
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict');
            $table->decimal('buy_price', 15, 2)->default(0);
            $table->decimal('sell_price', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->text('item_description')->nullable();
            $table->timestamps();
        });

        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('unit_code')->unique();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('from_unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('to_unit_id')->constrained('units')->onDelete('cascade');
            $table->integer('conversion_value');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_group');
        Schema::dropIfExists('items_category');
        Schema::dropIfExists('units');
        Schema::dropIfExists('items');
        Schema::dropIfExists('unit_conversions');
    }
};
