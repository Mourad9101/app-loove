-- Recréer les clés étrangères pour la table matches
ALTER TABLE matches
ADD CONSTRAINT matches_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
ADD CONSTRAINT matches_ibfk_2 FOREIGN KEY (liked_user_id) REFERENCES users(id) ON DELETE CASCADE; 