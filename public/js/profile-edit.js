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
        // Créer un champ caché pour indiquer quelle photo supprimer
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