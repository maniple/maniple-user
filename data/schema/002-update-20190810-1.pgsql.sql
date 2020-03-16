ALTER TABLE users ADD COLUMN is_locked INTEGER NOT NULL DEFAULT 0 CHECK (is_locked IN (0, 1));
ALTER TABLE users ADD COLUMN signup_ip VARCHAR(64);

UPDATE users u SET signup_ip = (
    SELECT ip_addr FROM registrations r
    WHERE r.status = 'CONFIRMED'
      AND r.email = u.email
);
