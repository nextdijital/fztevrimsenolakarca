-- Fzt. Evrim Şenol Akarca Veritabanı Şeması
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Yöneticiler Tablosu
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Site Ayarları Tablosu
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key_name` VARCHAR(100) NOT NULL UNIQUE,
  `key_value` TEXT NULL,
  `group_name` VARCHAR(50) DEFAULT 'general',
  `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Sayfa İçerikleri Tablosu (Dinamik Düzenlenebilir Alanlar)
CREATE TABLE IF NOT EXISTS `pages_content` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `page_key` VARCHAR(50) NOT NULL,
  `section_key` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) NULL,
  `subtitle` VARCHAR(255) NULL,
  `content` LONGTEXT NULL,
  `extra_data` JSON NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_page_section` (`page_key`, `section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Hizmetler Tablosu (6 Ana Uzmanlık ve Biofeedback)
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT NULL,
  `full_content` LONGTEXT NULL,
  `bullet_points` JSON NULL,
  `has_biofeedback` TINYINT(1) DEFAULT 0,
  `biofeedback_title` VARCHAR(255) NULL,
  `biofeedback_content` LONGTEXT NULL,
  `biofeedback_bullets` JSON NULL,
  `image_url` VARCHAR(255) NULL,
  `display_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_slug` (`slug`),
  INDEX `idx_order` (`display_order`),
  INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Sıkça Sorulan Sorular Tablosu
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `display_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_faq_order` (`display_order`),
  INDEX `idx_faq_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. İletişim ve Randevu Talepleri Tablosu
CREATE TABLE IF NOT EXISTS `contact_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `service_interest` VARCHAR(150) NULL,
  `message` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `status` VARCHAR(20) DEFAULT 'new',
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_req_status` (`status`),
  INDEX `idx_req_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Rate Limiting Tablosu
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `action_key` VARCHAR(50) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_action_ip` (`action_key`, `ip_address`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
