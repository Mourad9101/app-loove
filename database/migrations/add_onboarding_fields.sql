-- Désactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Supprimer la table temporaire si elle existe
DROP TABLE IF EXISTS users_temp;

-- Créer une table temporaire avec la nouvelle structure
CREATE TABLE users_temp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('H', 'F', 'NB') NOT NULL,
    age INT NOT NULL,
    city VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    gemstone VARCHAR(50) NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    bio TEXT,
    orientation ENUM('heterosexual', 'homosexual', 'bisexual', 'pansexual', 'asexual'),
    looking_for ENUM('relationship', 'friendship', 'casual'),
    interests JSON,
    has_completed_onboarding BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Copier les données existantes
INSERT INTO users_temp (
    id, first_name, last_name, email, password, gender, age,
    city, latitude, longitude, gemstone, image, bio,
    created_at, updated_at
)
SELECT 
    id, first_name, last_name, email, password, gender, age,
    city, latitude, longitude, gemstone, image, bio,
    created_at, updated_at
FROM users;

-- Supprimer l'ancienne table
DROP TABLE users;

-- Renommer la nouvelle table
RENAME TABLE users_temp TO users;

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1; 