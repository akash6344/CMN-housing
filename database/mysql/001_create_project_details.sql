-- =====================================================================
-- CMN Housing — project_details table
-- Covers: Step 1 Basic Details, Step 2 Smart Bargain Settings,
--         Step 3 Amenities & Features
-- Run BEFORE: 002_create_unit_configurations.sql
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- project_details
-- Stores Project Basic Details (Step 1), Smart Bargain Settings (Step 2),
-- and Amenities & Features as a JSON array (Step 3).
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `project_details` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Optional builder/developer user account ID',

    -- Step 1: Project Basic Details
    `name` VARCHAR(255) NOT NULL COMMENT 'Project Name (e.g., The Pinnacle Residences)',
    `tagline` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Project Tagline (e.g., Luxury Living. Smarter Prices.)',
    `builder_name` VARCHAR(255) NOT NULL COMMENT 'Builder / Developer Name',
    `location` VARCHAR(255) NOT NULL COMMENT 'Project Location (City, Locality, Landmark)',
    `maps_link` TEXT NULL DEFAULT NULL COMMENT 'Google Maps Link',
    `project_type` VARCHAR(100) NOT NULL DEFAULT 'Residential Apartment' COMMENT 'Residential Apartment, Villa Community, Plotting, Commercial',
    `project_status` VARCHAR(100) NOT NULL DEFAULT 'Under Construction' COMMENT 'Under Construction, Ready to Move, Upcoming',
    `possession_date` DATE NULL DEFAULT NULL COMMENT 'Target Possession Date',
    `rera_number` VARCHAR(100) NULL DEFAULT NULL COMMENT 'RERA Registration Number (e.g., P02400012345)',
    `towers` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Total Number of Towers',
    `total_units` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Total Number of Units in Project',
    `land_area` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Total Land Area (e.g., 10 Acres)',

    -- Step 2: Smart Bargain Settings
    `smart_bargain_enabled` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Flag to enable/disable AI Smart Bargain',
    `min_expected_price` DECIMAL(15, 2) NULL DEFAULT NULL COMMENT 'Minimum Expected Price (₹)',
    `target_price` DECIMAL(15, 2) NULL DEFAULT NULL COMMENT 'Target Desired Price (₹)',
    `max_price` DECIMAL(15, 2) NULL DEFAULT NULL COMMENT 'Maximum Quoted Price (₹)',

    -- Step 3: Amenities & Features
    `amenities` JSON NULL COMMENT 'JSON array of selected and custom amenities (e.g., ["Clubhouse", "Swimming Pool"])',

    -- Additional Metadata / Workflow
    `description` TEXT NULL DEFAULT NULL COMMENT 'Detailed project overview / description',
    `highlights` JSON NULL COMMENT 'Key highlights bullet points stored as JSON array',
    `status` ENUM('draft', 'under_review', 'published', 'archived') NOT NULL DEFAULT 'draft' COMMENT 'Project review and publication status',

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes for high-performance querying
    INDEX `idx_project_name` (`name`),
    INDEX `idx_builder_name` (`builder_name`),
    INDEX `idx_location` (`location`),
    INDEX `idx_project_type` (`project_type`),
    INDEX `idx_project_status` (`project_status`),
    INDEX `idx_status` (`status`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Stores main project basic details, bargain settings, and amenities';

SET FOREIGN_KEY_CHECKS = 1;
