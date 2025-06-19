<div style="max-width: 300px; margin: 0 auto;">
    <input type="text" 
           name="city" 
           id="cityInput"
           placeholder="Votre ville"
           required
           style="width: 100%;
                  padding: 15px;
                  border: 2px solid #eee;
                  border-radius: 12px;
                  font-size: 16px;
                  margin-bottom: 20px;">
</div>

<p class="step-description">
    Nous utiliserons cette information pour vous montrer des profils près de chez vous
</p>

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
        locationButton.style.cssText = `
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: none;
            border: 2px solid #FF3366;
            color: #FF3366;
            border-radius: 20px;
            cursor: pointer;
        `;

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

<style>
    body, .onboarding-bg {
        background: #f8f9fa;
        min-height: 100vh;
    }
    .onboarding-card, .location-card {
        max-width: 500px;
        margin: 40px auto 0 auto;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 4px 16px rgba(108, 92, 231, 0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    input[type="text"]:focus, input[type="number"]:focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 0 3px #6c5ce766;
    }
    button[type="submit"] {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #6c5ce7;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    button[type="submit"]:hover:not(:disabled) {
        background-color: #a29bfe;
    }
</style> 