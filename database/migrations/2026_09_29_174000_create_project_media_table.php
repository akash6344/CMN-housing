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
        if (!Schema::hasTable('project_media')) {
            Schema::create('project_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_detail_id')->constrained('project_details')->cascadeOnDelete();
                $table->string('category', 50)->default('gallery'); // elevation, amenities, sample_flat, site_photo, gallery, floor_plan_2d, floor_plan_3d, video, virtual_tour, general
                $table->string('title')->nullable();
                $table->string('file_name')->nullable();
                $table->text('file_path')->nullable();
                $table->text('file_url');
                $table->string('mime_type', 100)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->boolean('is_cover')->default(false);
                $table->integer('sort_order')->default(0);
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['project_detail_id', 'category']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_media');
    }
};
