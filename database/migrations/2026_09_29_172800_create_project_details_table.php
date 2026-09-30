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
        if (!Schema::hasTable('project_details')) {
            Schema::create('project_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();

                // Step 1: Project Basic Details
                $table->string('name');
                $table->string('tagline')->nullable();
                $table->string('builder_name');
                $table->string('location');
                $table->text('maps_link')->nullable();
                $table->string('project_type', 100)->default('Residential Apartment');
                $table->string('project_status', 100)->default('Under Construction');
                $table->date('possession_date')->nullable();
                $table->string('rera_number', 100)->nullable();
                $table->unsignedInteger('towers')->nullable();
                $table->unsignedInteger('total_units')->nullable();
                $table->string('land_area', 100)->nullable();

                // Step 2: Smart Bargain Settings
                $table->boolean('smart_bargain_enabled')->default(true);
                $table->decimal('min_expected_price', 15, 2)->nullable();
                $table->decimal('target_price', 15, 2)->nullable();
                $table->decimal('max_price', 15, 2)->nullable();

                // Step 3: Amenities & Features
                $table->json('amenities')->nullable();

                // Additional Metadata / Workflow
                $table->text('description')->nullable();
                $table->json('highlights')->nullable();
                $table->enum('status', ['draft', 'under_review', 'published', 'archived'])->default('draft');

                $table->timestamps();

                $table->index('name', 'idx_project_name');
                $table->index('builder_name', 'idx_builder_name');
                $table->index('location', 'idx_location');
                $table->index('project_type', 'idx_project_type');
                $table->index('project_status', 'idx_project_status');
                $table->index('status', 'idx_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
