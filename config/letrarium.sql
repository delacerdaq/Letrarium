CREATE DATABASE Letrarium;

USE Letrarium;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    terms BOOLEAN NOT NULL DEFAULT 0
);
select * from users;

CREATE TABLE poems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    visibility ENUM('public', 'restricted') NOT NULL,
    author_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);
select * from poems;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

ALTER TABLE poems
ADD COLUMN category_id INT,
ADD FOREIGN KEY (category_id) REFERENCES categories(id);

INSERT INTO categories (name) VALUES
    ('Amor'),
    ('Natureza'),
    ('Tristeza'),
    ('Felicidade'),
    ('Inspiracional'),
    ('Amizade'),
    ('Reflexão'),
    ('Humor'),
    ('Vida'),
    ('Mistério');
    
CREATE TABLE profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    profile_picture VARCHAR(255),
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

select * from tags;
select * from poem_tags;

CREATE TABLE poem_tags (
    poem_id INT,
    tag_id INT,
    PRIMARY KEY (poem_id, tag_id),
    FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

ALTER TABLE poem_tags
ADD CONSTRAINT fk_poem_id
FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE;

SELECT * FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_NAME = 'poem_tags' AND CONSTRAINT_SCHEMA = 'letrarium';

SELECT @@foreign_key_checks;

ALTER TABLE poem_tags
DROP PRIMARY KEY,
ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST,
ADD CONSTRAINT fk_poem_id
FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE,
ADD CONSTRAINT fk_tag_id
FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE;







    
    

