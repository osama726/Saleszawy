<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doc_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('doc_sections')->restrictOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('version', 20)->nullable();
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['section_id', 'slug']);
            $table->index(['status', 'sort_order']);
        });

        if ($this->supportsFullText()) {
            Schema::table('doc_articles', function (Blueprint $table) {
                $table->fullText(['title', 'content']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_articles');
    }

    private function supportsFullText(): bool
    {
        return in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true);
    }
};
