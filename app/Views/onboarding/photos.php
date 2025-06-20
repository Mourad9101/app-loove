<form method="POST" action="<?= BASE_URL ?>/onboarding" enctype="multipart/form-data" id="photoForm">
    <div class="photo-upload-grid">
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="photo-upload-box" data-index="<?= $i ?>">
                <input type="file" name="photos[]" accept="image/*" id="photo<?= $i ?>" <?= $i <= 2 ? 'required' : '' ?> class="photo-input">
                <label for="photo<?= $i ?>" class="upload-label">
                    <div class="preview-container">
                        <img src="" class="preview-image" style="display: none;">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            <span class="upload-text"><?= $i <= 2 ? 'Photo requise' : 'Ajouter une photo' ?></span>
                        </div>
                    </div>
                </label>
            </div>
        <?php endfor; ?>
    </div>

    <p class="step-description">
        Conseil : Choisissez des photos qui vous mettent en valeur. Les profils avec plusieurs photos ont plus de succès !
    </p>

    <button type="submit" id="submitPhotos" class="next-button">Continuer</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('photoForm');
    const photoInputs = document.querySelectorAll('.photo-input');
    
    photoInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                const previewImage = this.parentElement.querySelector('.preview-image');
                const uploadIcon = this.parentElement.querySelector('.upload-icon');
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    uploadIcon.style.display = 'none';
                }
                
                reader.readAsDataURL(file);
            }
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Vérifier si au moins 2 photos sont sélectionnées
        const filesSelected = document.querySelectorAll('.photo-input[required]');
        let validFiles = true;
        
        filesSelected.forEach(input => {
            if (!input.files || !input.files[0]) {
                validFiles = false;
            }
        });
        
        if (!validFiles) {
            alert('Veuillez sélectionner au moins 2 photos pour continuer.');
            return;
        }

        // Si tout est ok, soumettre le formulaire
        this.submit();
    });
});
</script> 

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 