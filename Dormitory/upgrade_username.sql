USE dormitory_management;

ALTER TABLE users ADD COLUMN IF NOT EXISTS username VARCHAR(40) NULL AFTER id;

UPDATE users
SET username = CASE
    WHEN email = 'admin@dorm.com' THEN 'admin'
    WHEN email = 'tenant@dorm.com' THEN 'tenant'
    ELSE CONCAT('user_', id)
END
WHERE username IS NULL OR username = '';

ALTER TABLE users MODIFY username VARCHAR(40) NOT NULL;

SET @has_username_unique = (
    SELECT COUNT(*)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'users'
      AND column_name = 'username'
      AND non_unique = 0
);
SET @add_username_unique = IF(@has_username_unique = 0, 'ALTER TABLE users ADD UNIQUE KEY uq_users_username (username)', 'SELECT 1');
PREPARE username_unique_statement FROM @add_username_unique;
EXECUTE username_unique_statement;
DEALLOCATE PREPARE username_unique_statement;

SELECT id, username, email, role FROM users ORDER BY id;
