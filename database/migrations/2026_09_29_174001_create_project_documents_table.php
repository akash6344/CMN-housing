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
        if (!Schema::hasTable('project_documents')) {
            Schema::create('project_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_detail_id')->constrained('project_details')->cascadeOnDelete();
                $table->string('category', 50)->default('other'); // brochure, price_list, rera, approval, agreement, other
                $table->string('title');
                $table->string('document_number', 100)->nullable();
                $table->string('file_name')->nullable();
                $table->text('file_path')->nullable();
                $table->text('file_url')->nullable();
                $table->string('mime_type', 100)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
                $table->date('expires_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['project_detail_id', 'category']);
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
