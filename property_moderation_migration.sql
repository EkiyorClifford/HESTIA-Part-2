ALTER TABLE properties
ADD COLUMN approval_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending' AFTER status,
ADD COLUMN rejection_reason TEXT NULL AFTER approval_status;
