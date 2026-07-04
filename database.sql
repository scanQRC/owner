-- ==========================================================
-- SCANME QR
-- Production Database Schema v1.0
-- Database : scanmeqr
-- Engine    : InnoDB
-- Charset   : utf8mb4
-- ==========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS scanmeqr;

CREATE DATABASE scanmeqr
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE scanmeqr;

-- ==========================================================
-- USERS
-- ==========================================================

CREATE TABLE users (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(120) NOT NULL,

    mobile VARCHAR(15) NOT NULL UNIQUE,

    email VARCHAR(150) UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    profile_photo VARCHAR(255) DEFAULT NULL,

    email_verified TINYINT(1) NOT NULL DEFAULT 0,

    mobile_verified TINYINT(1) NOT NULL DEFAULT 0,

    account_status ENUM(
        'ACTIVE',
        'INACTIVE',
        'BLOCKED'
    ) NOT NULL DEFAULT 'ACTIVE',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_users_mobile
ON users(mobile);

CREATE INDEX idx_users_email
ON users(email);

-- ==========================================================
-- VEHICLES
-- ==========================================================

CREATE TABLE vehicles (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    uvid CHAR(16) NOT NULL UNIQUE,

    qr_token CHAR(64) NOT NULL UNIQUE,

    registration_number VARCHAR(20) NOT NULL UNIQUE,

    chassis_number VARCHAR(50) NOT NULL,

    engine_number VARCHAR(50) NOT NULL,

    manufacturer VARCHAR(80),

    model VARCHAR(80),

    vehicle_type ENUM(
        'CAR',
        'BIKE',
        'SCOOTER',
        'BUS',
        'TRUCK',
        'OTHER'
    ) DEFAULT 'CAR',

    color VARCHAR(40),

    registration_date DATE,

    status ENUM(
        'ACTIVE',
        'PARKED',
        'LOST',
        'STOLEN',
        'EMERGENCY'
    ) DEFAULT 'ACTIVE',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vehicle_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_vehicle_status
ON vehicles(status);

CREATE INDEX idx_vehicle_user
ON vehicles(user_id);

-- ==========================================================
-- EMERGENCY CONTACTS
-- ==========================================================

CREATE TABLE emergency_contacts (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    contact_name VARCHAR(120) NOT NULL,

    relationship VARCHAR(80),

    mobile VARCHAR(15) NOT NULL,

    alternate_mobile VARCHAR(15) DEFAULT NULL,

    priority_order TINYINT UNSIGNED DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_emergency_vehicle
        FOREIGN KEY (vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_emergency_vehicle
ON emergency_contacts(vehicle_id);

-- ==========================================================
-- SUBSCRIPTIONS
-- ==========================================================

CREATE TABLE subscriptions (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    plan_name VARCHAR(50) NOT NULL,

    start_date DATE NOT NULL,

    expiry_date DATE NOT NULL,

    amount DECIMAL(10,2) DEFAULT 0.00,

    payment_status ENUM(
        'PENDING',
        'PAID',
        'FAILED'
    ) DEFAULT 'PENDING',

    subscription_status ENUM(
        'ACTIVE',
        'EXPIRED',
        'CANCELLED'
    ) DEFAULT 'ACTIVE',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_subscription_vehicle
        FOREIGN KEY(vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_subscription_vehicle
ON subscriptions(vehicle_id);

-- ==========================================================
-- QR SCAN LOGS
-- ==========================================================

CREATE TABLE scan_logs (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    ip_address VARCHAR(45),

    user_agent TEXT,

    latitude DECIMAL(10,7),

    longitude DECIMAL(10,7),

    city VARCHAR(100),

    state VARCHAR(100),

    country VARCHAR(100),

    CONSTRAINT fk_scan_vehicle
        FOREIGN KEY(vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_scan_vehicle
ON scan_logs(vehicle_id);

CREATE INDEX idx_scan_date
ON scan_logs(scanned_at);

-- ==========================================================
-- ADMINS
-- ==========================================================

CREATE TABLE admins (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(120) NOT NULL,

    username VARCHAR(50) NOT NULL UNIQUE,

    email VARCHAR(150) UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    role ENUM(
        'SUPER_ADMIN',
        'ADMIN',
        'SUPPORT'
    ) DEFAULT 'ADMIN',

    account_status ENUM(
        'ACTIVE',
        'INACTIVE'
    ) DEFAULT 'ACTIVE',

    last_login DATETIME DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- VEHICLE DOCUMENTS
-- ==========================================================

CREATE TABLE vehicle_documents (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    document_type ENUM(
        'RC',
        'INSURANCE',
        'PUC',
        'DRIVING_LICENSE',
        'OTHER'
    ) NOT NULL,

    document_file VARCHAR(255) NOT NULL,

    expiry_date DATE DEFAULT NULL,

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_document_vehicle
        FOREIGN KEY(vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_documents_vehicle
ON vehicle_documents(vehicle_id);

-- ==========================================================
-- AUDIT LOGS
-- ==========================================================

CREATE TABLE audit_logs (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    admin_id BIGINT UNSIGNED DEFAULT NULL,

    action VARCHAR(255) NOT NULL,

    reference_table VARCHAR(100),

    reference_id BIGINT UNSIGNED,

    ip_address VARCHAR(45),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_audit_admin
        FOREIGN KEY(admin_id)
        REFERENCES admins(id)
        ON DELETE SET NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_audit_admin
ON audit_logs(admin_id);

-- ==========================================================
-- APPLICATION SETTINGS
-- ==========================================================

CREATE TABLE app_settings (

    id INT AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(100) NOT NULL UNIQUE,

    setting_value TEXT,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- DATABASE VERSION
-- ==========================================================

CREATE TABLE database_version (

    id INT PRIMARY KEY,

    version VARCHAR(20) NOT NULL,

    installed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO database_version (id, version)
VALUES (1, '1.0');

-- ==========================================================
-- ENABLE FOREIGN KEYS
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 1;

COMMIT;

