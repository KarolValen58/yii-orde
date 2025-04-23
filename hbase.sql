DROP DATABASE IF EXISTS habits_tracker;

CREATE DATABASE IF NOT EXISTS habits_tracker;
USE habits_tracker;

CREATE TABLE IF NOT EXISTS `user` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `auth_key` VARCHAR(32) NOT NULL,
    `status` SMALLINT NOT NULL DEFAULT 10,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `category` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `color` VARCHAR(7) DEFAULT '#3498db',
    `icon` VARCHAR(50) DEFAULT 'check',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `habit` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `category_id` INT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `frequency_type` ENUM('daily', 'weekly', 'monthly', 'custom') NOT NULL DEFAULT 'daily',
    `frequency_value` VARCHAR(50) DEFAULT NULL,
    `target_value` DECIMAL(10,2) DEFAULT 1.00,
    `unit` VARCHAR(50) DEFAULT NULL,
    `reminder_time` TIME DEFAULT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE DEFAULT NULL,
    `status` TINYINT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `category`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `habit_log` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `habit_id` INT NOT NULL,
    `log_date` DATE NOT NULL,
    `value` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
    `notes` TEXT,
    `mood` TINYINT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `habit_date` (`habit_id`, `log_date`),
    FOREIGN KEY (`habit_id`) REFERENCES `habit`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `goal` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `habit_id` INT DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `target_value` DECIMAL(10,2) NOT NULL,
    `achieved_value` DECIMAL(10,2) DEFAULT 0.00,
    `unit` VARCHAR(50) DEFAULT NULL,
    `start_date` DATE NOT NULL,
    `target_date` DATE NOT NULL,
    `completion_date` DATE DEFAULT NULL,
    `status` ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`habit_id`) REFERENCES `habit`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `category` (`name`, `description`, `color`, `icon`) VALUES
('Salud', 'Hábitos relacionados con la salud física y mental', '#e74c3c', 'heart'),
('Finanzas', 'Hábitos para el control y mejora financiera', '#2ecc71', 'money-bill'),
('Educación', 'Actividades de aprendizaje y desarrollo intelectual', '#f39c12', 'book'),
('Social', 'Relaciones personales y actividades sociales', '#9b59b6', 'users'),
('Productividad', 'Organización y eficiencia en tareas diarias', '#1abc9c', 'tasks');

INSERT INTO `user` (`username`, `email`, `password_hash`, `auth_key`, `status`) VALUES
('admin', 'admin@example.com', '$2y$13$FKFUZGtT9Fqn2Asbg0vkwOuS5HqkOk/Xbi8IVNfSUf9dOKgzQu4Y.', 'admin_auth_key_123456', 10);
