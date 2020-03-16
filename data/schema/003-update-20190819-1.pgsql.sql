-- Store timestamps as BIGINT
ALTER TABLE users ALTER COLUMN created_at                      TYPE BIGINT;
ALTER TABLE users ALTER COLUMN last_password_change            TYPE BIGINT;
ALTER TABLE users ALTER COLUMN last_password_reset             TYPE BIGINT;
ALTER TABLE users ALTER COLUMN password_reset_token_expires_at TYPE BIGINT;

ALTER TABLE password_resets ALTER COLUMN created_at TYPE BIGINT;
ALTER TABLE password_resets ALTER COLUMN expires_at TYPE BIGINT;

ALTER TABLE registrations ALTER COLUMN created_at   TYPE BIGINT;
ALTER TABLE registrations ALTER COLUMN expires_at   TYPE BIGINT;
ALTER TABLE registrations ALTER COLUMN confirmed_at TYPE BIGINT;
ALTER TABLE registrations ALTER COLUMN verified_at  TYPE BIGINT;

-- Don't store settings update time
ALTER TABLE user_settings DROP COLUMN saved_at;
