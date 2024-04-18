<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('wallet_id');
        $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        $table->unsignedBigInteger('category_id');
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        $table->decimal('amount', 15, 2);
        $table->string('transaction_type', 10);
        $table->text('description')->nullable();
        $table->timestamp('transaction_date')->useCurrent();
        $table->timestamps();
    });

    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
