<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

// A product can now belong to several categories. products.category_id stays as the "main" category.
return new class extends Migration {
    public function up(): void {
        Schema::create('category_product', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unique(['category_id', 'product_id']);
        });
        // Keep existing products linked to the category they already had.
        DB::table('products')->select('id', 'category_id')->orderBy('id')->each(fn ($p) =>
            DB::table('category_product')->insert(['category_id' => $p->category_id, 'product_id' => $p->id]));
    }
    public function down(): void { Schema::dropIfExists('category_product'); }
};
