-- roles
CREATE TABLE roles (

    role_id         INTEGER PRIMARY KEY AUTO_INCREMENT,

    -- role name is purely informative and can be assigned by the end-user
    name            VARCHAR(255) NOT NULL UNIQUE,

    -- role description is even more informative, and as such, optional
    description     VARCHAR(255)

) ENGINE=InnoDB CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';


CREATE TABLE perms (

    perm_id         INTEGER PRIMARY KEY AUTO_INCREMENT,

    -- permission key for internal identification
    perm_key        VARCHAR(255) NOT NULL UNIQUE,

    -- permission description
    description     VARCHAR(255)

) ENGINE=InnoDB CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';


CREATE TABLE role_perms (

    role_id         INTEGER NOT NULL REFERENCES roles (role_id),

    perm_id         INTEGER NOT NULL REFERENCES perms (perm_id),

    UNIQUE (role_id, perm_id)

) ENGINE=InnoDB CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';


CREATE TABLE users (

    user_id         INTEGER PRIMARY KEY AUTO_INCREMENT,

    username        VARCHAR(255) NOT NULL UNIQUE,

    password        VARCHAR(128) NOT NULL,

    email           VARCHAR(255) NOT NULL UNIQUE,

    first_name      VARCHAR(255) NOT NULL,

    last_name       VARCHAR(255) NOT NULL,

    middle_name     VARCHAR(255),

    created_at      INTEGER NOT NULL,

    is_active       INTEGER NOT NULL DEFAULT 1
                    CHECK (is_active IN (0, 1)),

    last_password_change INTEGER,

    last_password_reset  INTEGER,

    password_reset_token VARCHAR(128) UNIQUE,

    password_reset_token_expires_at INTEGER

) ENGINE=InnoDB CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';


CREATE TABLE user_roles (

    user_id         INTEGER NOT NULL,
                    CONSTRAINT user_roles_user_id_fkey
                        FOREIGN KEY (user_id) REFERENCES users (user_id),

    role_id         INTEGER NOT NULL,
                    CONSTRAINT user_roles_role_id_fkey
                        FOREIGN KEY (role_id) REFERENCES roles (role_id),

    PRIMARY KEY (user_id, role_id)

) ENGINE=InnoDB CHARACTER SET 'UTF8' COLLATE 'utf8_general_ci';


-- password resets
CREATE TABLE password_resets (

    reset_id        VARCHAR(255) PRIMARY KEY,

    created_at      INTEGER NOT NULL,

    expires_at      INTEGER,

    ip_addr         VARCHAR(64) NOT NULL,

    user_id         INTEGER NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users (user_id)

) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



-- user registrations
CREATE TABLE registrations (

    -- unique registration token
    reg_id              VARCHAR(255) PRIMARY KEY,

    -- registration processing status
    status              ENUM('PENDING', 'CONFIRMED', 'ACCEPTED', 'REJECTED', 'INVALIDATED') NOT NULL
                        DEFAULT 'PENDING',

    -- registration time (epoch)
    created_at          INTEGER NOT NULL,

    -- expiration time (epoch)
    expires_at          INTEGER,

    -- email address confirmation time (epoch)
    confirmed_at        INTEGER,

    -- request verification time (epoch)
    verified_at         INTEGER,

    -- if registraion verification is on, this stores the id of a user
    -- which performed the verification
    verified_by         INTEGER,

    -- accept/rejection reason
    verification_note   VARCHAR(255),

    -- IP address the registration was made from (IPv4 or IPv6)
    ip_addr             VARCHAR(64) NOT NULL,

    -- id of related record in users table after this registration is accepted
    user_id             INTEGER UNIQUE,

    -- store email, to avoid multiple confirmed registrations for this email
    -- a confirmation for an email address automatically invalidates all other
    -- confirmations for this email, and prevents new registration requests
    -- to be created for this email
    email               VARCHAR(255) NOT NULL,

    -- data provided upon registration (json encoded)
    data                TEXT,

    FOREIGN KEY (verified_by) REFERENCES users (user_id),

    FOREIGN KEY (user_id) REFERENCES users (user_id)

) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;



CREATE TABLE user_settings (

    user_id         INTEGER NOT NULL,

    -- The index limit is 767 bytes (not characters) for InnoDB table. And since MySQL's
    -- utf8mb4 character set (which the rest of the world calls utf8) needs (at most)
    -- 4 bytes per character you can only index up to VARCHAR(191)
    name            VARCHAR(191) NOT NULL,

    value           TEXT NOT NULL,

    saved_at        INTEGER NOT NULL,

    PRIMARY KEY (user_id, name),

    FOREIGN KEY (user_id) REFERENCES users (user_id)

) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
