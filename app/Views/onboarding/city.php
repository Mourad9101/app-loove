<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection de la ville - EverGem</title>
    <style>
body, .onboarding-bg {
    background: #f8f9fa;
    min-height: 100vh;
}
.city-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 1rem;
}
.city-wrapper {
    background-color: #fff;
    border-radius: 0.5rem;
    padding: 2.5rem;
    box-shadow: 0 4px 16px rgba(108, 92, 231, 0.10);
}
.city-header {
    text-align: center;
    margin-bottom: 3rem;
}
.city-header h2 {
    color: #6c5ce7;
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
.city-subtitle {
    color: #a29bfe;
    font-size: 1rem;
}
.city-input-container {
    position: relative;
    margin-bottom: 2rem;
}
.search-box {
    position: relative;
    margin-bottom: 1.5rem;
}
.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c5ce7;
    width: 24px;
    height: 24px;
}
#citySearch {
    width: 100%;
    padding: 1.25rem 1.25rem 1.25rem 3.5rem;
    border: 2px solid #a29bfe;
    border-radius: 0.5rem;
    font-size: 1.1rem;
    color: #4a5568;
    transition: all 0.3s ease;
    background-color: #f8fafc;
}
#citySearch:focus {
    outline: none;
    border-color: #6c5ce7;
    box-shadow: 0 0 0 3px #6c5ce766;
    background-color: #ffffff;
}
#citySearch::placeholder {
    color: #a0aec0;
}
.search-results {
    position: absolute;
    top: calc(100% + 0.5rem);
    left: 0;
    right: 0;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 20px rgba(108, 92, 231, 0.10);
    max-height: 250px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    border: 1px solid #a29bfe;
}
.search-result-item {
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f0f4f8;
    font-size: 1rem;
    color: #4a5568;
}
.search-result-item:last-child {
    border-bottom: none;
}
.search-result-item:hover {
    background-color: #f3e8ff;
    color: #6c5ce7;
}
.selected-city-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 1.5rem 0;
    padding: 1.25rem;
    background-color: #f3e8ff;
    border-radius: 0.5rem;
    color: #6c5ce7;
    font-size: 1.1rem;
    font-weight: 500;
    border: 2px solid #a29bfe;
}
.location-icon {
    color: #6c5ce7;
    width: 24px;
    height: 24px;
}
.popular-cities {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #f0f4f8;
}
.popular-cities h3 {
    color: #6c5ce7;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}
.city-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}
.city-tag {
    padding: 0.75rem 1.5rem;
    background-color: #f7fafc;
    border: 2px solid #a29bfe;
    border-radius: 0.5rem;
    color: #6c5ce7;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}
.city-tag:hover {
    background-color: #f3e8ff;
    border-color: #6c5ce7;
    color: #6c5ce7;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(108, 92, 231, 0.10);
}
button[type="submit"] {
    display: block;
    margin: 20px auto 0 auto;
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
button[type="submit"]:disabled {
    background-color: #cbd5e0;
    cursor: not-allowed;
    opacity: 0.7;
}
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}
.search-results.active {
    display: block;
    animation: fadeIn 0.3s ease-out;
}
/* Scrollbar personnalisé pour les résultats de recherche */
.search-results::-webkit-scrollbar {
    width: 8px;
}
.search-results::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 4px;
}
.search-results::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}
.search-results::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
input[type="text"]:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 3px #6c5ce766;
}
.onboarding-card, .city-card {
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
</head>
<body>
    <form method="POST" action="<?= BASE_URL ?>/onboarding" id="cityForm">
        <div class="city-container">
            <div class="city-wrapper">
                <div class="city-header">
                    <h2>Où habitez-vous ?</h2>
                    <p class="city-subtitle">Trouvez des personnes près de chez vous</p>
                </div>
                
                <div class="city-input-container">
                    <div class="search-box">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input 
                            type="text" 
                            id="citySearch" 
                            placeholder="Rechercher une ville..."
                            autocomplete="off"
                        >
                        <input type="hidden" name="city" id="selectedCity" required>
                        <input type="hidden" name="latitude" id="cityLat">
                        <input type="hidden" name="longitude" id="cityLng">
                    </div>
                    
                    <div id="searchResults" class="search-results"></div>
                    
                    <div class="selected-city-info" id="selectedCityInfo" style="display: none;">
                        <svg class="location-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span id="selectedCityDisplay"></span>
                    </div>

                    <div class="popular-cities">
                        <h3>Villes populaires</h3>
                        <div class="city-tags">
                            <button type="button" class="city-tag" data-city="Paris" data-lat="48.8566" data-lng="2.3522">
                                Paris
                            </button>
                            <button type="button" class="city-tag" data-city="Lyon" data-lat="45.7578" data-lng="4.8320">
                                Lyon
                            </button>
                            <button type="button" class="city-tag" data-city="Marseille" data-lat="43.2965" data-lng="5.3698">
                                Marseille
                            </button>
                            <button type="button" class="city-tag" data-city="Bordeaux" data-lat="44.8378" data-lng="-0.5792">
                                Bordeaux
                            </button>
                            <button type="button" class="city-tag" data-city="Toulouse" data-lat="43.6047" data-lng="1.4442">
                                Toulouse
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="submit-button" id="submitCity" disabled>Continuer</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('citySearch');
            const searchResults = document.getElementById('searchResults');
            const selectedCity = document.getElementById('selectedCity');
            const cityLat = document.getElementById('cityLat');
            const cityLng = document.getElementById('cityLng');
            const submitButton = document.getElementById('submitCity');
            const selectedCityInfo = document.getElementById('selectedCityInfo');
            const selectedCityDisplay = document.getElementById('selectedCityDisplay');
            const form = document.getElementById('cityForm');
            let timeout = null;

            // Fonction de recherche avec l'API
            async function searchCity(query) {
                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'none';
                    return;
                }

                try {
                    const response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&type=municipality&limit=5`);
                    const data = await response.json();

                    if (data.features && data.features.length > 0) {
                        searchResults.innerHTML = '';
                        data.features.forEach(feature => {
                            const city = feature.properties;
                            const div = document.createElement('div');
                            div.className = 'search-result-item';
                            div.textContent = `${city.city} (${city.postcode})`;
                            div.addEventListener('click', () => {
                                selectCity(city.city, feature.geometry.coordinates[1], feature.geometry.coordinates[0]);
                            });
                            searchResults.appendChild(div);
                        });
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="search-result-item">Aucun résultat</div>';
                        searchResults.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Erreur de recherche:', error);
                    searchResults.innerHTML = '<div class="search-result-item">Erreur de recherche</div>';
                    searchResults.style.display = 'block';
                }
            }

            // Fonction de sélection
            function selectCity(city, lat, lng) {
                selectedCity.value = city;
                cityLat.value = lat;
                cityLng.value = lng;
                searchInput.value = city;
                searchResults.style.display = 'none';
                selectedCityDisplay.textContent = city;
                selectedCityInfo.style.display = 'flex';
                submitButton.disabled = false;
            }

            // Écouteur d'événements pour l'input
            searchInput.addEventListener('input', (e) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    searchCity(e.target.value.trim());
                }, 300);

                // Réinitialiser la sélection
                selectedCity.value = '';
                submitButton.disabled = true;
                selectedCityInfo.style.display = 'none';
            });

            // Gestion des villes populaires
            document.querySelectorAll('.city-tag').forEach(button => {
                button.addEventListener('click', () => {
                    const { city, lat, lng } = button.dataset;
                    selectCity(city, lat, lng);
                });
            });

            // Fermer les résultats en cliquant ailleurs
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.city-input-container')) {
                    searchResults.style.display = 'none';
                }
            });

            // Empêcher la soumission sur Entrée
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });

            // Validation du formulaire
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!selectedCity.value) {
                    alert('Veuillez sélectionner une ville dans la liste');
                    return;
                }
                form.submit();
            });
        });
    </script>
</body>
</html> 