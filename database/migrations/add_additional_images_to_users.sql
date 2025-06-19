-- Ajout de la colonne additional_images pour stocker les images supplémentaires
ALTER TABLE users
ADD COLUMN additional_images JSON AFTER image; 