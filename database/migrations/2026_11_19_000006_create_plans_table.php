<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->nullable();
            $table->char('currency', 3)->default('EGP');
            $table->unsignedSmallInteger('trial_days')->default(0);
            $table->unsignedInteger('max_sales_users')->nullable();
            $table->unsignedInteger('max_leads')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('badge', 100)->nullable();
            $table->string('cta_url', 2048)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('draft');
            $table->timestamps();

            $table->index(['status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
