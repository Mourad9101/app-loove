<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Modifier mon profil</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/profile/edit" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Prénom</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Nom</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age">Âge</label>
                                    <input type="number" class="form-control" id="age" name="age" 
                                           value="<?= htmlspecialchars($user['age']) ?>" required min="18" max="120">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Genre</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="H" <?= $user['gender'] === 'H' ? 'selected' : '' ?>>Homme</option>
                                        <option value="F" <?= $user['gender'] === 'F' ? 'selected' : '' ?>>Femme</option>
                                        <option value="NB" <?= $user['gender'] === 'NB' ? 'selected' : '' ?>>Non-binaire</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="city">Ville</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="<?= htmlspecialchars($user['city']) ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gemstone">Pierre précieuse préférée</label>
                            <select class="form-control" id="gemstone" name="gemstone" required>
                                <option value="Diamond" <?= $user['gemstone'] === 'Diamond' ? 'selected' : '' ?>>Diamant</option>
                                <option value="Ruby" <?= $user['gemstone'] === 'Ruby' ? 'selected' : '' ?>>Rubis</option>
                                <option value="Emerald" <?= $user['gemstone'] === 'Emerald' ? 'selected' : '' ?>>Émeraude</option>
                                <option value="Sapphire" <?= $user['gemstone'] === 'Sapphire' ? 'selected' : '' ?>>Saphir</option>
                                <option value="Amethyst" <?= $user['gemstone'] === 'Amethyst' ? 'selected' : '' ?>>Améthyste</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bio">À propos de moi</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                            <a href="<?= BASE_URL ?>/profile" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.25);
}

.btn-primary {
    background-color: #4299e1;
    border-color: #4299e1;
}

.btn-primary:hover {
    background-color: #3182ce;
    border-color: #3182ce;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
    background-color: #a0aec0;
    border-color: #a0aec0;
}

.btn-secondary:hover {
    background-color: #718096;
    border-color: #718096;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style> 