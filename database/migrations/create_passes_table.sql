-- Création de la table des passes
CREATE TABLE IF NOT EXISTS passes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    passed_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (passed_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_pass (user_id, passed_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 