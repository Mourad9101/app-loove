<?php
namespace app\Models;

use app\Core\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO users (
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
                    updated_at
                ) VALUES (
                    :email,
                    :password,
                    :first_name,
                    :last_name,
                    :gender,
                    :age,
                    :city,
                    :gemstone,
                    :image,
                    :bio,
                    NOW(),
                    NOW()
                )";
        
        try {
            // Log des données reçues
            error_log("Tentative d'inscription - Données reçues : " . print_r($data, true));
            
            // Vérifie si le mot de passe est déjà haché
            $password = (substr($data['password'], 0, 4) === '$2y$') 
                ? $data['password']  // Si déjà haché, utiliser tel quel
                : password_hash($data['password'], PASSWORD_DEFAULT);  // Sinon, le hacher
            
            // Séparer le nom complet en prénom et nom
            $nameParts = explode(' ', trim($data['name']), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            
            $stmt = $this->db->prepare($sql);
            $params = [
                ':email' => $data['email'],
                ':password' => $password,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':gender' => isset($data['gender']) ? $data['gender'] : 'H',
                ':age' => isset($data['age']) ? $data['age'] : 18,
                ':city' => isset($data['city']) ? $data['city'] : 'Paris',
                ':gemstone' => isset($data['gemstone']) ? $data['gemstone'] : 'Diamond',
                ':image' => isset($data['image']) ? $data['image'] : 'default.jpg',
                ':bio' => isset($data['bio']) ? $data['bio'] : 'Nouvelle personne sur EverGem'
            ];
            
            // Log des paramètres de la requête
            error_log("Paramètres de la requête : " . print_r($params, true));
            
            $success = $stmt->execute($params);
            
            if (!$success) {
                error_log("Erreur lors de l'exécution de la requête : " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Exception PDO lors de l'inscription : " . $e->getMessage());
            error_log("Trace : " . $e->getTraceAsString());
            return false;
        }
    }

    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']); // Ne jamais renvoyer le mot de passe
                return $user;
            }
            
            return false;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                unset($user['password']); // Ne jamais renvoyer le mot de passe
            }
            
            return $user;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        // Liste des champs autorisés à être mis à jour
        $allowedFields = [
            'first_name',
            'last_name',
            'gender',
            'age',
            'city',
            'gemstone',
            'bio',
            'image',
            'latitude',
            'longitude'
        ];

        $fields = [];
        $params = [':id' => $id];

        // Log des données reçues
        error_log("Tentative de mise à jour - Données reçues : " . print_r($data, true));

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                // Validation spécifique pour le genre
                if ($key === 'gender' && !in_array($value, ['H', 'F', 'NB'])) {
                    error_log("Valeur de genre invalide : " . $value);
                    continue;
                }
                
                // Validation spécifique pour la pierre précieuse
                if ($key === 'gemstone' && !in_array($value, ['Diamond', 'Ruby', 'Emerald', 'Sapphire', 'Amethyst'])) {
                    error_log("Valeur de pierre précieuse invalide : " . $value);
                    continue;
                }

                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        // Ajouter la mise à jour du timestamp
        $fields[] = "updated_at = NOW()";

        if (empty($fields)) {
            error_log("Aucun champ valide à mettre à jour");
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                error_log("Erreur lors de l'exécution de la requête: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Exception dans update: " . $e->getMessage());
            return false;
        }
    }

    public function getPotentialMatches(int $userId): array {
        try {
            error_log("=== DÉBUT getPotentialMatches pour l'utilisateur $userId ===");
            
            // Vérifier si la table matches existe
            $checkTable = "SHOW TABLES LIKE 'matches'";
            $stmt = $this->db->prepare($checkTable);
            $stmt->execute();
            $tableExists = $stmt->rowCount() > 0;
            error_log("Table matches existe : " . ($tableExists ? "Oui" : "Non"));

            // Récupérer d'abord tous les utilisateurs déjà likés
            $likedUsers = [];
            if ($tableExists) {
                $likedQuery = "SELECT liked_user_id FROM matches WHERE user_id = :userId";
                $stmt = $this->db->prepare($likedQuery);
                $stmt->execute([':userId' => $userId]);
                $likedUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);
                error_log("Utilisateurs déjà likés : " . implode(", ", $likedUsers));
            }

            // Construire la requête principale
            $sql = "SELECT * FROM users WHERE id != :userId AND has_completed_onboarding = 1";
            
            // Ajouter la condition pour exclure les utilisateurs déjà likés
            if (!empty($likedUsers)) {
                $sql .= " AND id NOT IN (" . implode(",", $likedUsers) . ")";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT 10";
            
            error_log("Requête SQL : " . $sql);
            error_log("Paramètres : " . print_r([':userId' => $userId], true));
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':userId' => $userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Nombre de résultats trouvés : " . count($results));
            
            // Retirer les mots de passe des résultats
            foreach ($results as &$result) {
                unset($result['password']);
            }
            
            error_log("=== FIN getPotentialMatches ===");
            return $results;
        } catch (\PDOException $e) {
            error_log("Erreur dans getPotentialMatches : " . $e->getMessage());
            error_log("Trace : " . $e->getTraceAsString());
            return [];
        }
    }

    public function hasCompletedOnboarding($userId) {
        $sql = "SELECT has_completed_onboarding FROM users WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['has_completed_onboarding'] == 1;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateOnboardingStep($userId, $data) {
        $fields = [];
        $params = [':id' => $userId];

        // Conversion des données d'onboarding vers la structure de la base de données
        if (isset($data['gender'])) {
            $fields[] = "gender = :gender";
            $params[':gender'] = $data['gender'];
        }

        if (isset($data['birth_date'])) {
            // Calculer l'âge à partir de la date de naissance
            $birthDate = new \DateTime($data['birth_date']);
            $today = new \DateTime();
            $age = $birthDate->diff($today)->y;
            
            $fields[] = "age = :age";
            $params[':age'] = $age;
        }

        if (isset($data['orientation'])) {
            $fields[] = "orientation = :orientation";
            $params[':orientation'] = $data['orientation'];
        }

        if (isset($data['looking_for'])) {
            $fields[] = "looking_for = :looking_for";
            $params[':looking_for'] = $data['looking_for'];
        }

        if (isset($data['interests']) && is_array($data['interests'])) {
            $fields[] = "interests = :interests";
            $params[':interests'] = json_encode($data['interests']);
        }

        if (isset($data['bio'])) {
            $fields[] = "bio = :bio";
            $params[':bio'] = $data['bio'];
        }

        if (isset($data['city'])) {
            $fields[] = "city = :city";
            $params[':city'] = $data['city'];

            if (isset($data['latitude']) && isset($data['longitude'])) {
                $fields[] = "latitude = :latitude";
                $fields[] = "longitude = :longitude";
                $params[':latitude'] = $data['latitude'];
                $params[':longitude'] = $data['longitude'];
            }
        }

        // Marquer explicitement l'onboarding comme complété
        $fields[] = "has_completed_onboarding = 1";
        
        if (empty($fields)) {
            error_log("Aucun champ à mettre à jour dans updateOnboardingStep");
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            error_log("SQL updateOnboardingStep: " . $sql);
            error_log("Params updateOnboardingStep: " . print_r($params, true));
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                error_log("Erreur lors de l'exécution de la requête updateOnboardingStep: " . print_r($stmt->errorInfo(), true));
            } else {
                error_log("Mise à jour réussie - has_completed_onboarding défini à 1 pour l'utilisateur " . $userId);
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Exception dans updateOnboardingStep: " . $e->getMessage());
            return false;
        }
    }
} 