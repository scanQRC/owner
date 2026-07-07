-- ==========================================================
-- SCANME QR
-- Production Database Schema
-- Version : 1.1
-- Database : scanmeqr
-- Engine   : InnoDB
-- Charset  : utf8mb4
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
-- Vehicle Owners Only
-- ==========================================================

CREATE TABLE users (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(120) NOT NULL,

    mobile VARCHAR(15) NOT NULL UNIQUE,

    email VARCHAR(150) DEFAULT NULL UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    profile_photo VARCHAR(255) DEFAULT NULL,

    email_verified TINYINT(1) NOT NULL DEFAULT 0,

    mobile_verified TINYINT(1) NOT NULL DEFAULT 0,

    account_status ENUM(
        'ACTIVE',
        'INACTIVE',
        'BLOCKED'
    ) NOT NULL DEFAULT 'ACTIVE',

    last_login DATETIME DEFAULT NULL,

    deleted_at DATETIME DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_users_mobile
ON users(mobile);

CREATE INDEX idx_users_email
ON users(email);

CREATE INDEX idx_users_status
ON users(account_status);

CREATE INDEX idx_users_last_login
ON users(last_login);

-- ==========================================================
-- ADMINS
-- Admin Authentication Only
-- ==========================================================

CREATE TABLE admins (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(120) NOT NULL,

    username VARCHAR(50) NOT NULL UNIQUE,

    mobile VARCHAR(15) DEFAULT NULL UNIQUE,

    email VARCHAR(150) DEFAULT NULL UNIQUE,

    password_hash VARCHAR(255) NOT NULL,

    role ENUM(
        'SUPER_ADMIN',
        'ADMIN',
        'SUPPORT'
    ) NOT NULL DEFAULT 'ADMIN',

    account_status ENUM(
        'ACTIVE',
        'INACTIVE',
        'BLOCKED'
    ) NOT NULL DEFAULT 'ACTIVE',

    failed_login_attempts SMALLINT UNSIGNED NOT NULL DEFAULT 0,

    last_login DATETIME DEFAULT NULL,

    last_login_ip VARCHAR(45) DEFAULT NULL,

    locked_until DATETIME DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_admin_username
ON admins(username);

CREATE INDEX idx_admin_email
ON admins(email);

CREATE INDEX idx_admin_mobile
ON admins(mobile);

CREATE INDEX idx_admin_status
ON admins(account_status);

CREATE INDEX idx_admin_role
ON admins(role);

-- ==========================================================
-- DEFAULT SUPER ADMIN
--
-- Password must be replaced after first installation.
-- Replace PASSWORD_HASH_HERE before production deployment.
-- ==========================================================

INSERT INTO admins (

    full_name,
    username,
    mobile,
    email,
    password_hash,
    role,
    account_status

) VALUES (

    'Super Administrator',
    'superadmin',
    NULL,
    'admin@scanme.local',
    'PASSWORD_HASH_HERE',
    'SUPER_ADMIN',
    'ACTIVE'

);

-- ==========================================================
-- PART-1 END
-- NEXT : VEHICLES
-- ==========================================================

-- ==========================================================
-- VEHICLES
-- ==========================================================

CREATE TABLE vehicles (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    uvid CHAR(16) NOT NULL UNIQUE,

    qr_token CHAR(64) NOT NULL UNIQUE,

    registration_number VARCHAR(20) NOT NULL UNIQUE,

    chassis_number VARCHAR(50) NOT NULL UNIQUE,

    engine_number VARCHAR(50) NOT NULL UNIQUE,

    manufacturer VARCHAR(80) NOT NULL,

    model VARCHAR(80) NOT NULL,

    variant VARCHAR(80) DEFAULT NULL,

    vehicle_type ENUM(
        'CAR',
        'BIKE',
        'SCOOTER',
        'BUS',
        'TRUCK',
        'AUTO',
        'TRACTOR',
        'OTHER'
    ) NOT NULL DEFAULT 'CAR',

    fuel_type ENUM(
        'PETROL',
        'DIESEL',
        'CNG',
        'EV',
        'HYBRID',
        'OTHER'
    ) DEFAULT 'PETROL',

    color VARCHAR(40) DEFAULT NULL,

    registration_date DATE DEFAULT NULL,

    status ENUM(
        'ACTIVE',
        'PARKED',
        'LOST',
        'STOLEN',
        'EMERGENCY'
    ) NOT NULL DEFAULT 'ACTIVE',

    remarks TEXT DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vehicle_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_vehicle_user
ON vehicles(user_id);

CREATE INDEX idx_vehicle_registration
ON vehicles(registration_number);

CREATE INDEX idx_vehicle_status
ON vehicles(status);

CREATE INDEX idx_vehicle_type
ON vehicles(vehicle_type);

CREATE INDEX idx_vehicle_manufacturer
ON vehicles(manufacturer);

CREATE INDEX idx_vehicle_model
ON vehicles(model);

-- ==========================================================
-- PART-2 END
-- NEXT : EMERGENCY CONTACTS + VEHICLE DOCUMENTS
-- ==========================================================

-- ==========================================================
-- EMERGENCY CONTACTS
-- ==========================================================

CREATE TABLE emergency_contacts (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    contact_name VARCHAR(120) NOT NULL,

    relationship VARCHAR(80) DEFAULT NULL,

    mobile VARCHAR(15) NOT NULL,

    alternate_mobile VARCHAR(15) DEFAULT NULL,

    email VARCHAR(150) DEFAULT NULL,

    address VARCHAR(255) DEFAULT NULL,

    priority_order TINYINT UNSIGNED NOT NULL DEFAULT 1,

    is_primary TINYINT(1) NOT NULL DEFAULT 0,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_emergency_vehicle
        FOREIGN KEY (vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_emergency_vehicle
ON emergency_contacts(vehicle_id);

CREATE INDEX idx_emergency_mobile
ON emergency_contacts(mobile);

CREATE INDEX idx_emergency_priority
ON emergency_contacts(priority_order);

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

    document_number VARCHAR(100) DEFAULT NULL,

    document_file VARCHAR(255) NOT NULL,

    issue_date DATE DEFAULT NULL,

    expiry_date DATE DEFAULT NULL,

    verification_status ENUM(
        'PENDING',
        'VERIFIED',
        'REJECTED'
    ) NOT NULL DEFAULT 'PENDING',

    verified_by BIGINT UNSIGNED DEFAULT NULL,

    verified_at DATETIME DEFAULT NULL,

    remarks TEXT DEFAULT NULL,

    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_document_vehicle
        FOREIGN KEY(vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_document_verified_by
        FOREIGN KEY(verified_by)
        REFERENCES admins(id)
        ON DELETE SET NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_documents_vehicle
ON vehicle_documents(vehicle_id);

CREATE INDEX idx_documents_type
ON vehicle_documents(document_type);

CREATE INDEX idx_documents_status
ON vehicle_documents(verification_status);

-- ==========================================================
-- PART-3 END
-- NEXT : SUBSCRIPTIONS
-- ==========================================================

-- ==========================================================
-- SUBSCRIPTIONS
-- ==========================================================

CREATE TABLE subscriptions (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    plan_name VARCHAR(100) NOT NULL,

    transaction_id VARCHAR(100) DEFAULT NULL UNIQUE,

    payment_gateway VARCHAR(50) DEFAULT NULL,

    amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    currency CHAR(3) NOT NULL DEFAULT 'INR',

    start_date DATE NOT NULL,

    expiry_date DATE NOT NULL,

    renewed_from BIGINT UNSIGNED DEFAULT NULL,

    payment_status ENUM(
        'PENDING',
        'PAID',
        'FAILED',
        'REFUNDED'
    ) NOT NULL DEFAULT 'PENDING',

    subscription_status ENUM(
        'ACTIVE',
        'EXPIRED',
        'CANCELLED'
    ) NOT NULL DEFAULT 'ACTIVE',

    notes TEXT DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_subscription_vehicle
        FOREIGN KEY(vehicle_id)
        REFERENCES vehicles(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_subscription_previous
        FOREIGN KEY(renewed_from)
        REFERENCES subscriptions(id)
        ON DELETE SET NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_subscription_vehicle
ON subscriptions(vehicle_id);

CREATE INDEX idx_subscription_status
ON subscriptions(subscription_status);

CREATE INDEX idx_subscription_payment
ON subscriptions(payment_status);

CREATE INDEX idx_subscription_expiry
ON subscriptions(expiry_date);

CREATE INDEX idx_subscription_transaction
ON subscriptions(transaction_id);

-- ==========================================================
-- PART-4 END
-- NEXT : SCAN LOGS
-- ==========================================================

-- ==========================================================
-- SCAN LOGS
-- ==========================================================

CREATE TABLE scan_logs (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vehicle_id BIGINT UNSIGNED NOT NULL,

    scan_type ENUM(
        'NORMAL',
        'EMERGENCY'
    ) NOT NULL DEFAULT 'NORMAL',

    scanned_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    ip_address VARCHAR(45) DEFAULT NULL,

    user_agent TEXT DEFAULT NULL,

    browser VARCHAR(100) DEFAULT NULL,

    platform VARCHAR(100) DEFAULT NULL,

    device_type ENUM(
        'DESKTOP',
        'MOBILE',
        'TABLET',
        'OTHER'
    ) DEFAULT 'OTHER',

    latitude DECIMAL(10,7) DEFAULT NULL,

    longitude DECIMAL(10,7) DEFAULT NULL,

    city VARCHAR(100) DEFAULT NULL,

    state VARCHAR(100) DEFAULT NULL,

    country VARCHAR(100) DEFAULT NULL,

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

CREATE INDEX idx_scan_type
ON scan_logs(scan_type);

CREATE INDEX idx_scan_city
ON scan_logs(city);

CREATE INDEX idx_scan_country
ON scan_logs(country);

-- ==========================================================
-- PART-5 END
-- NEXT : AUDIT LOGS + APP SETTINGS + DATABASE VERSION
-- ==========================================================

-- ==========================================================
-- AUDIT LOGS
-- ==========================================================

CREATE TABLE audit_logs (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    admin_id BIGINT UNSIGNED DEFAULT NULL,

    action VARCHAR(255) NOT NULL,

    reference_table VARCHAR(100) DEFAULT NULL,

    reference_id BIGINT UNSIGNED DEFAULT NULL,

    old_values LONGTEXT DEFAULT NULL,

    new_values LONGTEXT DEFAULT NULL,

    ip_address VARCHAR(45) DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_audit_admin
        FOREIGN KEY (admin_id)
        REFERENCES admins(id)
        ON DELETE SET NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_audit_admin
ON audit_logs(admin_id);

CREATE INDEX idx_audit_date
ON audit_logs(created_at);

-- ==========================================================
-- APPLICATION SETTINGS
-- ==========================================================

CREATE TABLE app_settings (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(100) NOT NULL UNIQUE,

    setting_value TEXT DEFAULT NULL,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO app_settings (setting_key, setting_value) VALUES
('site_name','ScanMe QR'),
('site_url','https://scanme.page.gd'),
('default_timezone','Asia/Kolkata'),
('default_language','en'),
('qr_status','enabled'),
('registration','enabled');

-- ==========================================================
-- DATABASE VERSION
-- ==========================================================

CREATE TABLE database_version (

    id INT PRIMARY KEY,

    version VARCHAR(20) NOT NULL,

    installed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO database_version (id, version)
VALUES (1, '1.1');

-- ==========================================================
-- ENABLE FOREIGN KEYS
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 1;

COMMIT;

-- ==========================================================
-- DATABASE SCHEMA COMPLETED
-- SCANME QR
-- Production Database Schema v1.1
-- ==========================================================
