-- Création de la base de données
CREATE DATABASE IF NOT EXISTS evergem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE evergem;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default.jpg',
    bio TEXT,
    birthdate DATE,
    gender ENUM('M', 'F', 'Other'),
    preferences JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_premium BOOLEAN DEFAULT FALSE,
    daily_matches_count INT DEFAULT 0,
    last_match_date DATE NULL,
    INDEX email_idx (email)
) ENGINE=InnoDB;

-- Table des matchs
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    liked_user_id INT NOT NULL,
    matched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (liked_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_match (user_id, liked_user_id)
) ENGINE=InnoDB;

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table des passes (swipe gauche)
CREATE TABLE IF NOT EXISTS passes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    passed_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (passed_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_pass (user_id, passed_user_id)
) ENGINE=InnoDB;

-- Index pour optimiser les recherches
CREATE INDEX idx_users_gender ON users(gender);
CREATE INDEX idx_users_age ON users(age);
CREATE INDEX idx_users_city ON users(city);
CREATE INDEX idx_users_gemstone ON users(gemstone);
CREATE INDEX idx_messages_conversation ON messages(sender_id, receiver_id);
CREATE INDEX idx_messages_read_status ON messages(read_at); 

-- Table des abonnements
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    stripe_subscription_id VARCHAR(255) NOT NULL UNIQUE,
    plan_type ENUM('premium', 'premium_plus') DEFAULT 'premium',
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'cancelled', 'past_due', 'unpaid') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_subscription (user_id, status),
    INDEX idx_stripe_subscription (stripe_subscription_id)
) ENGINE=InnoDB; 