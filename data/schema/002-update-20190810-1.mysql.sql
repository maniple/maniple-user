-- region CONVERT TO CHARACTER SET utf8mb4
-- Convert all indexed VARCHAR(255) columns to VARCHAR(191) to avoid:
-- Error #1071 - Specified key was too long; max key length is 767 bytes
ALTER TABLE roles CHANGE name name VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE roles CHANGE description description VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE roles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


ALTER TABLE perms CHANGE perm_key perm_key VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE perms CHANGE description description VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE perms CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


ALTER TABLE role_perms CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


ALTER TABLE user_roles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


ALTER TABLE password_resets CHANGE reset_id reset_id VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE password_resets CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


ALTER TABLE registrations CHANGE reg_id reg_id VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE registrations CHANGE verification_note verification_note VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE registrations CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


ALTER TABLE users CHANGE username username VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE users CHANGE email email VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE users CHANGE password password VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE users CHANGE first_name first_name VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE users CHANGE last_name last_name VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE users CHANGE middle_name middle_name VARCHAR(191)
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE users CONVERT TO
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- endregion

ALTER TABLE users ADD COLUMN is_locked TINYINT(1) NOT NULL DEFAULT 0 AFTER is_active;

ALTER TABLE users ADD COLUMN signup_ip VARCHAR(64) AFTER is_locked;

UPDATE users SET signup_ip = (
    SELECT ip_addr FROM registrations r
    WHERE r.status = 'CONFIRMED'
      AND r.email = users.email COLLATE utf8mb4_unicode_ci
);
