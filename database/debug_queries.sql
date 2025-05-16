-- Vérifier tous les utilisateurs et leur statut d'onboarding
SELECT id, email, first_name, has_completed_onboarding 
FROM users 
ORDER BY id;

-- Vérifier les utilisateurs qui devraient apparaître pour l'ID 11
SELECT id, email, first_name, has_completed_onboarding 
FROM users 
WHERE id != 11 
AND has_completed_onboarding = 1 
AND id NOT IN (SELECT liked_user_id FROM matches WHERE user_id = 11); 