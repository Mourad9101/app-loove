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

                        <!-- Section Photos -->
                        <div class="form-group mb-4">
                            <label>Mes Photos</label>
                            
                            <!-- Image principale actuelle -->
                            <div class="current-photos mb-3">
                                <h6>Photo principale actuelle :</h6>
                                <div class="current-main-photo">
                                    <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image']) ?>" 
                                         class="img-thumbnail" 
                                         alt="Photo principale actuelle"
                                         style="max-width: 200px; height: 200px; object-fit: cover;"
                                         onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                                </div>
                                <div class="mt-2">
                                    <label for="main_photo">Changer la photo principale :</label>
                                    <input type="file" class="form-control-file" id="main_photo" name="main_photo" accept="image/*">
                                </div>
                            </div>

                            <!-- Images supplémentaires actuelles -->
                            <?php if (!empty($user['additional_images'])): ?>
                                <?php 
                                $additionalImages = json_decode($user['additional_images'], true);
                                if (is_array($additionalImages) && !empty($additionalImages)):
                                ?>
                                    <div class="current-additional-photos mb-3">
                                        <h6>Photos supplémentaires actuelles :</h6>
                                        <div class="row">
                                            <?php foreach ($additionalImages as $index => $image): ?>
                                                <div class="col-md-3 mb-2">
                                                    <div class="position-relative">
                                                        <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($image) ?>" 
                                                             class="img-thumbnail" 
                                                             alt="Photo supplémentaire <?= $index + 1 ?>"
                                                             style="width: 100%; height: 120px; object-fit: cover;"
                                                             onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                                                onclick="removePhoto(<?= $index ?>)">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Ajouter de nouvelles photos -->
                            <div class="add-new-photos">
                                <h6>Ajouter de nouvelles photos :</h6>
                                <div class="row">
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <div class="col-md-3 mb-2">
                                            <div class="photo-upload-box" style="border: 2px dashed #ddd; border-radius: 8px; padding: 10px; text-align: center; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                                                <input type="file" name="additional_photos[]" accept="image/*" 
                                                       class="form-control-file" 
                                                       style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                                <div class="upload-placeholder">
                                                    <i class="fas fa-plus" style="font-size: 24px; color: #ccc;"></i>
                                                    <div style="font-size: 12px; color: #999;">Ajouter photo</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
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
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
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

.photo-upload-box {
    position: relative !important;
    overflow: hidden !important;
}
.add-new-photos .row {
    overflow: hidden;
}
.photo-upload-box input[type="file"] {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
    margin: 0;
    padding: 0;
}
</style> 
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'upload des nouvelles photos
    const photoInputs = document.querySelectorAll('input[name="additional_photos[]"]');
    
    photoInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                const uploadBox = this.parentElement;
                
                reader.onload = function(e) {
                    uploadBox.innerHTML = `
                        <img src="${e.target.result}" 
                             class="img-thumbnail" 
                             style="width: 100%; height: 120px; object-fit: cover;">
                        <input type="file" name="additional_photos[]" accept="image/*" 
                               class="form-control-file" 
                               style="position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                    `;
                    // Réattacher l'event listener au nouvel input
                    const newInput = uploadBox.querySelector('input[type="file"]');
                    newInput.addEventListener('change', arguments.callee);
                }
                
                reader.readAsDataURL(file);
            }
        });
    });

    // Gestion de l'upload de la photo principale
    const mainPhotoInput = document.getElementById('main_photo');
    if (mainPhotoInput) {
        mainPhotoInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                const currentPhoto = document.querySelector('.current-main-photo img');
                
                reader.onload = function(e) {
                    currentPhoto.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        });
    }
});

// Fonction pour supprimer une photo existante
function removePhoto(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_photo_index';
        hiddenInput.value = index;
        document.querySelector('form').appendChild(hiddenInput);
        // Masquer visuellement la photo
        const photoContainer = event.target.closest('.col-md-3');
        photoContainer.style.display = 'none';
    }
}
</script>
