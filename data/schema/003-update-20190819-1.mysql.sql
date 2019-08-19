-- Store timestamps as BIGINT
ALTER TABLE users MODIFY COLUMN created_at                      BIGINT NOT NULL;
ALTER TABLE users MODIFY COLUMN last_password_change            BIGINT;
ALTER TABLE users MODIFY COLUMN last_password_reset             BIGINT;
ALTER TABLE users MODIFY COLUMN password_reset_token_expires_at BIGINT;

ALTER TABLE password_resets MODIFY COLUMN created_at BIGINT NOT NULL;
ALTER TABLE password_resets MODIFY COLUMN expires_at BIGINT;

ALTER TABLE registrations MODIFY COLUMN created_at   BIGINT NOT NULL;
ALTER TABLE registrations MODIFY COLUMN expires_at   BIGINT;
ALTER TABLE registrations MODIFY COLUMN confirmed_at BIGINT;
ALTER TABLE registrations MODIFY COLUMN verified_at  BIGINT;

-- Don't store settings update time
ALTER TABLE user_settings DROP COLUMN saved_at;
