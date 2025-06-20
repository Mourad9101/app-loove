<form method="POST" action="<?= BASE_URL ?>/onboarding" class="onboarding-form">
    <div class="location-input-container">
    <input type="text" 
           name="city" 
           id="cityInput"
           placeholder="Votre ville"
           required
               class="city-input">
</div>

<p class="step-description">
    Nous utiliserons cette information pour vous montrer des profils près de chez vous
</p>

    <button type="submit" class="next-button" disabled>Continuer</button>
</form>

<script>
    const cityInput = document.querySelector('#cityInput');
    const nextButton = document.querySelector('.next-button');

    // Désactiver le bouton si le champ est vide
    cityInput.addEventListener('input', function() {
        nextButton.disabled = !this.value.trim();
    });

    // Désactiver le bouton au chargement
    nextButton.disabled = true;

    // Option : Utiliser la géolocalisation
    if ("geolocation" in navigator) {
        const locationButton = document.createElement('button');
        locationButton.type = 'button';
        locationButton.textContent = '📍 Utiliser ma position actuelle';
        locationButton.className = 'location-button';

        locationButton.addEventListener('click', function() {
            navigator.geolocation.getCurrentPosition(async function(position) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}&accept-language=fr`);
                    const data = await response.json();
                    
                    if (data.address) {
                        cityInput.value = data.address.city || data.address.town || data.address.village || '';
                        nextButton.disabled = !cityInput.value.trim();
                    }
                } catch (error) {
                    console.error('Erreur lors de la récupération de la ville:', error);
                }
            });
        });

        document.querySelector('.onboarding-form').insertBefore(
            locationButton,
            document.querySelector('.next-button')
        );
    }
</script>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 