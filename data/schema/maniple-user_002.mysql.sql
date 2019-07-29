ALTER TABLE users ADD COLUMN is_locked TINYINT(1) NOT NULL DEFAULT 0 AFTER is_active;

ALTER TABLE users ADD COLUMN signup_ip VARCHAR(64) AFTER is_locked;

UPDATE users SET signup_ip = (
    SELECT ip_addr FROM registrations r
    WHERE r.status = 'CONFIRMED'
      -- ERROR 1267 (HY000): Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and
      -- (utf8_general_ci,IMPLICIT) for operation '='
      AND r.email = users.email COLLATE utf8_unicode_ci
);
