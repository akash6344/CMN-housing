<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Project and unit data is managed directly in MySQL via:
     *   database/mysql/001_create_project_details.sql
     *   database/mysql/002_create_unit_configurations.sql
     */
    public function run(): void
    {
        // No seed data needed — schema and data handled via raw SQL files.
    }
}
