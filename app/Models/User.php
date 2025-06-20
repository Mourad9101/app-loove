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
                    google_id,
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
                    :google_id,
                    NOW(),
                    NOW()
                )";
        
        try {
            // Vérifie si le mot de passe est déjà haché
            $password = (isset($data['password']) && substr($data['password'], 0, 4) === '$2y$') 
                ? $data['password']
                : (isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null);
            
            // Séparer le nom complet en prénom et nom
            $nameParts = explode(' ', trim($data['name'] ?? ''), 2);
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
                ':bio' => isset($data['bio']) ? $data['bio'] : 'Nouvelle personne sur EverGem',
                ':google_id' => isset($data['google_id']) ? $data['google_id'] : null
            ];
            
            $success = $stmt->execute($params);
            
            if (!$success) {
                error_log("Erreur d'exécution de la requête CREATE USER: " . implode(" | ", $stmt->errorInfo()));
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
                unset($user['password']);
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
                unset($user['password']);
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
            'additional_images',
            'latitude',
            'longitude'
        ];

        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                // Validation spécifique pour le genre
                if ($key === 'gender' && !in_array($value, ['H', 'F', 'NB'])) {
                    continue;
                }
                
                // Validation spécifique pour la pierre précieuse
                if ($key === 'gemstone' && !in_array($value, ['Diamond', 'Ruby', 'Emerald', 'Sapphire', 'Amethyst'])) {
                    continue;
                }

                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        // Ajouter la mise à jour du timestamp
        $fields[] = "updated_at = NOW()";

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
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

    public function getPotentialMatches(int $userId, int $offset = 0, int $limit = 10, array $filters = []): array {
        try {
            // Récupérer tous les utilisateurs déjà interagis (likés, passés)
            $excludedUsers = [$userId];
            $likedQuery = "SELECT liked_user_id FROM matches WHERE user_id = :userId";
            $stmt = $this->db->prepare($likedQuery);
            $stmt->execute([':userId' => $userId]);
            $likedUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $excludedUsers = array_merge($excludedUsers, $likedUsers);
            $passedQuery = "SELECT passed_user_id FROM passes WHERE user_id = :userId";
            $stmt = $this->db->prepare($passedQuery);
            $stmt->execute([':userId' => $userId]);
            $passedUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $excludedUsers = array_merge($excludedUsers, $passedUsers);

            // Récupérer les informations de l'utilisateur actuel pour le calcul de distance et la pierre
            $currentUser = $this->findById($userId);
            $userLatitude = $currentUser['latitude'] ?? null;
            $userLongitude = $currentUser['longitude'] ?? null;
            $userGemstone = $currentUser['gemstone'] ?? null;

            $sql = "SELECT id, email, first_name, last_name, gender, age, city, gemstone, image, bio, is_premium, latitude, longitude, ";
            $sql .= "(6371 * ACOS(COS(RADIANS(:user_latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(:user_longitude)) + SIN(RADIANS(:user_latitude)) * SIN(RADIANS(latitude)))) AS distance, ";
            $sql .= "CASE WHEN gemstone = :user_gemstone THEN 1 ELSE 0 END AS same_gemstone ";
            $sql .= "FROM users WHERE has_completed_onboarding = 1 AND id != :userId";
            $params = [
                ':userId' => $userId,
                ':user_latitude' => $userLatitude,
                ':user_longitude' => $userLongitude,
                ':user_gemstone' => $userGemstone
            ];

            // Appliquer les filtres (identique à avant)
            if (!empty($filters)) {
                if (isset($filters['min_age']) && is_numeric($filters['min_age'])) {
                    $sql .= " AND age >= :min_age";
                    $params[':min_age'] = (int)$filters['min_age'];
                }
                if (isset($filters['max_age']) && is_numeric($filters['max_age'])) {
                    $sql .= " AND age <= :max_age";
                    $params[':max_age'] = (int)$filters['max_age'];
                }
                if (isset($filters['gender']) && in_array($filters['gender'], ['H', 'F', 'NB'])) {
                    $sql .= " AND gender = :gender";
                    $params[':gender'] = $filters['gender'];
                }
                if (isset($filters['gemstone']) && !empty($filters['gemstone'])) {
                    $sql .= " AND gemstone = :gemstone";
                    $params[':gemstone'] = $filters['gemstone'];
                }
                if (isset($filters['radius']) && is_numeric($filters['radius']) && $userLatitude !== null && $userLongitude !== null) {
                    $sql .= " AND (6371 * ACOS(COS(RADIANS(:user_latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(:user_longitude)) + SIN(RADIANS(:user_latitude)) * SIN(RADIANS(latitude)))) <= :radius";
                    $params[':radius'] = (float)$filters['radius'];
                }
            }
            /*
            if (!empty($excludedUsers)) {
                $placeholders = str_repeat('?,', count($excludedUsers) - 1) . '?';
                $sql .= " AND id NOT IN ($placeholders)";
                $params = array_merge($params, $excludedUsers);
            }
            */
            $sql .= " ORDER BY distance ASC, same_gemstone DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            foreach ($params as $key => &$val) {
                if (is_int($val)) {
                    $stmt->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $val);
                }
            }
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as &$result) {
                unset($result['password']);
            }
            return $results;
        } catch (\PDOException $e) {
            error_log("Erreur dans getPotentialMatches : " . $e->getMessage());
            error_log("Trace : " . $e->getTraceAsString());
            return [];
        }
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT id, email, first_name, last_name, gender, age, city, is_active, is_premium, is_admin, image, created_at, updated_at FROM users";
        try {
            error_log("Exécution de getAllUsers() - SQL: " . $sql);
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            error_log("getAllUsers() - Nombre d'utilisateurs trouvés: " . count($results));
            return $results;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de tous les utilisateurs : " . $e->getMessage());
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

        // Gestion des images
        if (isset($data['image'])) {
            $fields[] = "image = :image";
            $params[':image'] = $data['image'];
        }

        if (isset($data['additional_images']) && is_array($data['additional_images'])) {
            $fields[] = "additional_images = :additional_images";
            $params[':additional_images'] = json_encode($data['additional_images']);
        }

        // Marquer explicitement l'onboarding comme complété
        $fields[] = "has_completed_onboarding = 1";
        
        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
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

    public function updateDailyMatchCount(int $userId, int $count, string $date): bool
    {
        $sql = "UPDATE users SET daily_matches_count = :count, last_match_date = :date WHERE id = :userId";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':count' => $count,
                ':date' => $date,
                ':userId' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du compteur de matchs quotidiens : " . $e->getMessage());
            return false;
        }
    }

    public function createPass(int $userId, int $passedUserId): bool
    {
        $sql = "INSERT INTO passes (user_id, passed_user_id, created_at) VALUES (:user_id, :passed_user_id, NOW())";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':passed_user_id' => $passedUserId
            ]);
        } catch (\PDOException $e) {
            // Si c'est une erreur de clé dupliquée, on considère que c'est un succès
            if ($e->getCode() == 23000) {
                return true;
            }
            error_log("Erreur lors de la création du pass : " . $e->getMessage());
            return false;
        }
    }

    public function setUserStatus(int $userId, bool $isActive): bool
    {
        $sql = "UPDATE users SET is_active = :is_active WHERE id = :userId";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':is_active' => $isActive ? 1 : 0,
                ':userId' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser(int $userId): bool
    {
        $sql = "DELETE FROM users WHERE id = :userId";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':userId' => $userId]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function setIsAdmin(int $userId, bool $isAdmin): bool
    {
        $sql = "UPDATE users SET is_admin = :is_admin WHERE id = :userId";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':is_admin' => $isAdmin ? 1 : 0,
                ':userId' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut d'administrateur : " . $e->getMessage());
            return false;
        }
    }

    public function recordProfileView(int $viewerId, int $viewedId): bool
    {
        $sql = "INSERT INTO profile_views (viewer_id, viewed_id) VALUES (:viewer_id, :viewed_id)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':viewer_id' => $viewerId,
                ':viewed_id' => $viewedId
            ]);
        } catch (\PDOException $e) {
            // Si c'est une erreur de clé dupliquée (même vue le même jour), on ne fait rien.
            if ($e->getCode() == 23000) {
                return true;
            }
            error_log("Erreur lors de l'enregistrement de la vue de profil : " . $e->getMessage());
            return false;
        }
    }

    public function getProfileViews(int $viewedId): array
    {
        $sql = "SELECT pv.viewer_id, u.first_name, u.last_name, u.image, pv.view_date 
                FROM profile_views pv 
                JOIN users u ON pv.viewer_id = u.id 
                WHERE pv.viewed_id = :viewed_id 
                ORDER BY pv.view_date DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':viewed_id' => $viewedId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des vues de profil : " . $e->getMessage());
            return [];
        }
    }

    public function setIsPremium($userId, $isPremium) {
        $sql = "UPDATE users SET is_premium = :is_premium, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':is_premium' => $isPremium ? 1 : 0,
            ':id' => $userId
        ]);
    }

    public function updateResetToken($userId, $token, $expiry) {
        $sql = "UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':token' => $token,
                ':expiry' => $expiry,
                ':id' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur updateResetToken : " . $e->getMessage());
            return false;
        }
    }

    public function updatePasswordAndClearToken($userId, $password) {
        $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            return $stmt->execute([
                ':password' => $hashed,
                ':id' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur updatePasswordAndClearToken : " . $e->getMessage());
            return false;
        }
    }
} 