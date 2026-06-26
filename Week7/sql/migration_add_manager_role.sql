-- PitStop Parts: Add manager role
-- Run this on both local MySQL and Railway MySQL console
ALTER TABLE users MODIFY COLUMN role ENUM('customer','manager','admin') DEFAULT 'customer';
