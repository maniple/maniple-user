ALTER TABLE registrations RENAME TO signups;

ALTER TABLE signups RENAME CONSTRAINT registrations_pkey TO signups_pkey;
ALTER TABLE signups RENAME CONSTRAINT registrations_user_id_key TO signups_user_id_key;
ALTER TABLE signups RENAME CONSTRAINT registrations_status_check TO signups_status_check;
ALTER TABLE signups RENAME CONSTRAINT registrations_user_id_fkey TO signups_user_id_fkey;
ALTER TABLE signups RENAME CONSTRAINT registrations_verified_by_fkey TO signups_verified_by_fkey;

ALTER TABLE users DROP COLUMN signup_ip;
