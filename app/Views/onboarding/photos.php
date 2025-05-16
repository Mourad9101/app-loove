<form method="POST" action="<?= BASE_URL ?>/onboarding" enctype="multipart/form-data" id="photoForm">
    <div class="photo-upload-grid">
        <?php for ($i = 1; $i <= 6; $i++): ?>
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

    <button type="submit" id="submitPhotos">Continuer</button>
</form>

<style>
.photo-upload-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    padding: 1rem;
    max-width: 800px;
    margin: 0 auto;
}

.photo-upload-box {
    position: relative;
    aspect-ratio: 1;
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.photo-upload-box:hover {
    border-color: #4299e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.photo-input {
    display: none;
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    cursor: pointer;
    padding: 1rem;
}

.preview-container {
    width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

.upload-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: #4a5568;
}

.upload-text {
    font-size: 0.875rem;
    text-align: center;
    color: #4a5568;
}

button[type="submit"] {
    display: block;
    margin: 20px auto;
    padding: 15px 30px;
    background-color: #4299e1;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #3182ce;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.step-description {
    text-align: center;
    color: #4a5568;
    margin: 1rem 0;
    font-size: 0.875rem;
}
</style>

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