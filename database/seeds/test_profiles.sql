-- Insertion de profils de test
INSERT INTO users (
    email,
    password,
    first_name,
    last_name,
    gender,
    age,
    city,
    gemstone,
    image,
    bio,
    created_at,
    updated_at,
    has_completed_onboarding
) VALUES 
('sophie@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sophie', 'Martin', 'F', 28, 'Paris', 'Ruby', 'default.jpg', 'Passionnée d''art et de voyages', NOW(), NOW(), 1),
('lucas@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lucas', 'Bernard', 'H', 32, 'Lyon', 'Sapphire', 'default.jpg', 'Amateur de sport et de cuisine', NOW(), NOW(), 1),
('emma@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emma', 'Dubois', 'F', 25, 'Marseille', 'Emerald', 'default.jpg', 'Musicienne et voyageuse', NOW(), NOW(), 1),
('thomas@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Thomas', 'Petit', 'H', 30, 'Bordeaux', 'Diamond', 'default.jpg', 'Développeur passionné de technologie', NOW(), NOW(), 1),
('julie@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Julie', 'Robert', 'F', 27, 'Toulouse', 'Amethyst', 'default.jpg', 'Photographe et amoureuse de la nature', NOW(), NOW(), 1); 