<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_categories', function (Blueprint $table): void {
            $table->string('group', 100)->nullable()->index()->after('slug');
        });

        Schema::table('features', function (Blueprint $table): void {
            $table->string('group', 100)->nullable()->index()->after('category_id');
            $table->string('badge', 100)->nullable()->after('summary');
            $table->string('cta_label', 100)->nullable()->after('icon');
            $table->string('cta_url', 255)->nullable()->after('cta_label');
        });
    }

    public function down(): void
    {
        Schema::table('features', function (Blueprint $table): void {
            $table->dropIndex(['group']);
            $table->dropColumn(['group', 'badge', 'cta_label', 'cta_url']);
        });

        Schema::table('feature_categories', function (Blueprint $table): void {
            $table->dropIndex(['group']);
            $table->dropColumn('group');
        });
    }
};
