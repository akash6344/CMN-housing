-- =====================================================================
-- CMN Housing — unit_configurations table
-- Covers: Step 2 Unit Configuration & Pricing (BHKs, areas, pricing,
--         floor range, floor plans, room breakdown)
-- Depends on: 001_create_project_details.sql (must be run first)
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- unit_configurations
-- Stores Unit Configurations & Pricing for each project:
-- BHK types, Super Built-up Area, Carpet Area, Unit Price,
-- Availability, Floor Range, and optional Floor Plan URLs.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `unit_configurations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `project_detail_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key → project_details.id',

    -- Unit Configuration & Pricing details
    `unit_type` VARCHAR(100) NOT NULL COMMENT 'Unit type: 1 BHK, 2 BHK, 3 BHK, 4 BHK, Penthouse, etc.',
    `built_up_area` DECIMAL(10, 2) NOT NULL COMMENT 'Super Built-up Area (Sq.Ft)',
    `carpet_area` DECIMAL(10, 2) NOT NULL COMMENT 'Carpet Area (Sq.Ft)',
    `price` DECIMAL(15, 2) NOT NULL COMMENT 'Total Unit Price in INR (₹)',
    `price_per_sqft` DECIMAL(12, 2) NULL DEFAULT NULL COMMENT 'Calculated or specified Price per Sq.Ft (₹)',
    `available_units` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Number of units available in this configuration',
    `floor_range` VARCHAR(100) NULL DEFAULT 'All Floors' COMMENT 'Floor Range (e.g., 1st - 20th Floor)',
    `show_floor_plan` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Toggle to show 2D/3D floor plan for this unit type',
    `floor_plan_2d_url` VARCHAR(255) NULL DEFAULT NULL COMMENT 'File path or URL for 2D Floor Plan image',
    `floor_plan_3d_url` VARCHAR(255) NULL DEFAULT NULL COMMENT 'File path or URL for 3D Floor Plan image',
    `room_configurations` JSON NULL COMMENT 'Optional room-by-room breakdown with dimensions as JSON array',

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes & Foreign Key
    INDEX `idx_unit_project_id` (`project_detail_id`),
    INDEX `idx_unit_type` (`unit_type`),
    INDEX `idx_unit_price` (`price`),

    CONSTRAINT `fk_unit_configurations_project`
        FOREIGN KEY (`project_detail_id`)
        REFERENCES `project_details` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Stores unit types, built-up/carpet areas, pricing, and floor plan references per project';

SET FOREIGN_KEY_CHECKS = 1;
