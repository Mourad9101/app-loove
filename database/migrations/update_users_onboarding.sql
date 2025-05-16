-- Mise à jour des utilisateurs existants pour marquer l'onboarding comme complété
UPDATE users SET has_completed_onboarding = 1 WHERE has_completed_onboarding = 0; 