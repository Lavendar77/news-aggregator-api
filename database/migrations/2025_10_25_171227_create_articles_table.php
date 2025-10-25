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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('api_source')->comment('The API source of the article: App\Enums\ArticleApiSource');
            $table->string('news_source')->index();
            $table->string('title')->fulltext();
            $table->text('description')->fulltext();
            $table->longText('content')->fulltext();
            $table->string('url');
            $table->string('image_url')->nullable();
            $table->string('author')->nullable();
            $table->string('category')->nullable()->index();
            $table->timestamp('published_at')->index();
            $table->timestamps();

            $table->unique(['title', 'api_source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
