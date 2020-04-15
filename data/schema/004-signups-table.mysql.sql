RENAME TABLE registrations TO signups;

ALTER TABLE signups DROP FOREIGN KEY signups_ibfk_1;
ALTER TABLE signups DROP FOREIGN KEY signups_ibfk_2;

ALTER TABLE signups ADD CONSTRAINT signups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users (user_id);
ALTER TABLE signups ADD CONSTRAINT signups_verified_by_fkey FOREIGN KEY (user_id) REFERENCES users (user_id);

ALTER TABLE users DROP COLUMN signup_ip;
