<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique();
            $t->string('type', 10)->default('book');      // book | accessory
            $t->string('icon', 8)->nullable(); $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->string('type', 10)->default('book')->index();
            $t->string('title'); $t->string('author')->nullable(); $t->string('isbn')->nullable()->unique();
            $t->text('description')->nullable();
            $t->unsignedInteger('price');                 // cents
            $t->unsignedInteger('stock')->default(0);
            $t->unsignedSmallInteger('year')->nullable();
            $t->string('cover_color', 7)->default('#14213D');
            $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('email'); $t->string('phone'); $t->text('address');
            $t->unsignedInteger('total');
            $t->string('payment_method', 10)->default('cod');
            $t->string('payment_status', 10)->default('unpaid');
            $t->string('status', 20)->default('pending');
            $t->string('stripe_session')->nullable();
            $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained();
            $t->unsignedInteger('quantity'); $t->unsignedInteger('price');
        });
        Schema::create('subscribers', function (Blueprint $t) {
            $t->id(); $t->string('email')->unique(); $t->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email'); $t->string('subject');
            $t->text('message'); $t->timestamps();
        });
    }
    public function down(): void {
        foreach (['contact_messages', 'subscribers', 'order_items', 'orders', 'products', 'categories'] as $n) Schema::dropIfExists($n);
    }
};
