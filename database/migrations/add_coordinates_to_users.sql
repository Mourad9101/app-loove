-- Ajout des colonnes de coordonnées géographiques
ALTER TABLE users
ADD COLUMN latitude DECIMAL(10, 8) AFTER city,
ADD COLUMN longitude DECIMAL(11, 8) AFTER latitude; 