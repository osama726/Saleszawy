<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('author_name')->nullable()->after('author_id');
            $table->string('author_title')->nullable()->after('author_name');
            $table->boolean('is_featured')->default(false)->index()->after('reading_time');
            $table->json('tags')->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex(['is_featured']);
            $table->dropColumn(['author_name', 'author_title', 'is_featured', 'tags']);
        });
    }
};
