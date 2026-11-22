<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_blocks', function (Blueprint $table) {
            $table->string('key', 100)->nullable()->after('page_id');
        });

        // Backfill existing rows: key = type, then type-2, type-3 ... per page.
        $seen = [];

        DB::table('page_blocks')
            ->orderBy('page_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'page_id', 'type'])
            ->each(function (object $row) use (&$seen): void {
                $count = $seen[$row->page_id][$row->type] = ($seen[$row->page_id][$row->type] ?? 0) + 1;

                DB::table('page_blocks')
                    ->where('id', $row->id)
                    ->update(['key' => $count === 1 ? $row->type : $row->type . '-' . $count]);
            });

        Schema::table('page_blocks', function (Blueprint $table) {
            $table->unique(['page_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::table('page_blocks', function (Blueprint $table) {
            $table->dropUnique(['page_id', 'key']);
            $table->dropColumn('key');
        });
    }
};
