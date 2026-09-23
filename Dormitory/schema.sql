CREATE DATABASE IF NOT EXISTS dormitory_management
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dormitory_management;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS maintenance_tickets;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS invoices;
DROP TABLE IF EXISTS leases;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(40) NOT NULL UNIQUE,
  full_name VARCHAR(160) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(40) NULL,
  role ENUM('admin', 'tenant') NOT NULL DEFAULT 'tenant',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE rooms (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(40) NOT NULL UNIQUE,
  floor INT NOT NULL,
  capacity INT NOT NULL,
  occupied_beds INT NOT NULL DEFAULT 0,
  monthly_rate DECIMAL(10,2) NOT NULL,
  status ENUM('available', 'full', 'maintenance') NOT NULL DEFAULT 'available',
  CONSTRAINT chk_room_capacity CHECK (capacity >= 0),
  CONSTRAINT chk_room_occupied CHECK (occupied_beds >= 0 AND occupied_beds <= capacity)
) ENGINE=InnoDB;

CREATE TABLE leases (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  room_id INT UNSIGNED NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NULL,
  monthly_rent DECIMAL(10,2) NOT NULL,
  status ENUM('active', 'terminated') NOT NULL DEFAULT 'active',
  CONSTRAINT fk_leases_tenant FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_leases_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE invoices (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lease_id INT UNSIGNED NOT NULL,
  tenant_id INT UNSIGNED NOT NULL,
  month_year CHAR(7) NOT NULL,
  rent_amount DECIMAL(10,2) NOT NULL,
  utility_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  total_amount DECIMAL(10,2) NOT NULL,
  due_date DATE NOT NULL,
  status ENUM('unpaid', 'pending', 'paid', 'overdue') NOT NULL DEFAULT 'unpaid',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_invoice_lease_month (lease_id, month_year),
  CONSTRAINT fk_invoices_lease FOREIGN KEY (lease_id) REFERENCES leases(id) ON DELETE CASCADE,
  CONSTRAINT fk_invoices_tenant FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invoice_id INT UNSIGNED NOT NULL,
  tenant_id INT UNSIGNED NOT NULL,
  payment_method ENUM('gcash_api', 'gcash_manual') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  reference_number VARCHAR(120) NULL,
  paymongo_checkout_id VARCHAR(190) NULL,
  proof_image_url VARCHAR(255) NULL,
  status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  paid_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
  CONSTRAINT fk_payments_tenant FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE maintenance_tickets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  room_id INT UNSIGNED NOT NULL,
  category VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
  status ENUM('open', 'in_progress', 'resolved') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_tickets_tenant FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_tickets_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (id, username, full_name, email, password_hash, phone, role) VALUES
(1, 'admin', 'System Administrator', 'admin@dorm.com', '$2y$12$.Eagwbn6FFhXoR2NvP8vcOpgiYRutDc0SJnLOLeDhNuP0XYLjIEJ2', '09170000000', 'admin'),
(2, 'tenant', 'Juan Tenant', 'tenant@dorm.com', '$2y$12$99Gbckv7a8xsPyOyHnVwvOIXtRtcVnQwG1qZPlpUSlTA29ZlrO2Hu', '09171234567', 'tenant');

INSERT INTO rooms (id, room_number, floor, capacity, occupied_beds, monthly_rate, status) VALUES
(1, 'A-101', 1, 4, 1, 6500.00, 'available'),
(2, 'B-204', 2, 2, 2, 8000.00, 'full');

INSERT INTO leases (id, tenant_id, room_id, start_date, end_date, monthly_rent, status) VALUES
(1, 2, 1, '2026-09-01', NULL, 6500.00, 'active');

INSERT INTO invoices (id, lease_id, tenant_id, month_year, rent_amount, utility_amount, total_amount, due_date, status) VALUES
(1, 1, 2, '2026-09', 6500.00, 850.00, 7350.00, '2026-09-30', 'unpaid');

INSERT INTO maintenance_tickets (tenant_id, room_id, category, description, priority, status) VALUES
(2, 1, 'Plumbing', 'Bathroom faucet has a slow leak.', 'medium', 'open');
