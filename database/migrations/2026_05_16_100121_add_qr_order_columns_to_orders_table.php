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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('table_number')->nullable()->after('customer_name');
            $table->string('order_type')->default('pos')->after('table_number'); // 'pos' or 'qr'
            $table->string('payment_method')->default('cash')->after('order_type'); // 'cash' or 'qris'
            $table->string('status')->default('completed')->after('payment_method'); // 'completed' or 'pending'
            
            // Because qr orders might not have a user_id (kasir) initially, we need to make user_id nullable.
            // Let's modify the user_id column if needed, or we just rely on customer_id, wait, user_id is currently NOT NULL.
            // $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'table_number', 'order_type', 'payment_method', 'status']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
