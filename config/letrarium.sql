CREATE DATABASE Letrarium
	DEFAULT CHARACTER SET utf8mb4
	DEFAULT COLLATE utf8mb4_unicode_ci;
USE Letrarium;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    terms BOOLEAN NOT NULL DEFAULT 0
)ENGINE=InnoDB;
select * from users;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)ENGINE=InnoDB;

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

CREATE TABLE poems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    visibility ENUM('public', 'restricted') NOT NULL,
    author_id INT NOT NULL,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
)ENGINE=InnoDB;
select * from poems;
    
CREATE TABLE profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    profile_picture VARCHAR(255),
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)ENGINE=InnoDB;
select * from profile;

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)ENGINE=InnoDB;
select * from password_resets;

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
)ENGINE=InnoDB;
select * from tags;

CREATE TABLE poem_tags (
    poem_id INT,
    tag_id INT,
    PRIMARY KEY (poem_id, tag_id),
    FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
)ENGINE=InnoDB;
select * from poem_tags;

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    poem_id INT NOT NULL,
    liked BOOLEAN NOT NULL DEFAULT 1,  -- Campo para indicar se o poema foi curtido
    liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, poem_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- sendo testadas
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE poem_comments (
    poem_id INT,
    comment_id INT,
    user_id INT,  -- Adicionando o campo user_id como chave estrangeira
    PRIMARY KEY (poem_id, comment_id),
    FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE,
    FOREIGN KEY (comment_id) REFERENCES comments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Chave estrangeira para usuários
) ENGINE=InnoDB;















    
    

