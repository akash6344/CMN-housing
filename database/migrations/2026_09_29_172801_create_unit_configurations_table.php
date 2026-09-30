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
        if (!Schema::hasTable('unit_configurations')) {
            Schema::create('unit_configurations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_detail_id')->constrained('project_details')->cascadeOnDelete();
                $table->string('unit_type', 100);
                $table->decimal('built_up_area', 10, 2);
                $table->decimal('carpet_area', 10, 2);
                $table->decimal('price', 15, 2);
                $table->decimal('price_per_sqft', 12, 2)->nullable();
                $table->unsignedInteger('available_units')->default(1);
                $table->string('floor_range', 100)->default('All Floors')->nullable();
                $table->boolean('show_floor_plan')->default(true);
                $table->string('floor_plan_2d_url')->nullable();
                $table->string('floor_plan_3d_url')->nullable();
                $table->json('room_configurations')->nullable();
                $table->timestamps();

                $table->index('price', 'idx_unit_price');
                $table->index('unit_type', 'idx_unit_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_configurations');
    }
};
