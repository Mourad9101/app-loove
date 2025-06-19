<form method="POST" action="<?= BASE_URL ?>/onboarding" id="interestsForm">
    <div class="onboarding-card">
        <div class="interests-header">
            <h2>Tes centres d'intérêt</h2>
            <p class="interests-subtitle">Sélectionne ce qui te passionne&nbsp;!<br>Plus tu en choisis, plus tes suggestions seront personnalisées.</p>
        </div>
        <div class="option-grid">
            <?php
            $interests = [
                'Sport', 'Musique', 'Cinéma', 'Lecture', 'Voyage', 'Cuisine', 'Art', 'Jeux vidéo', 'Nature', 'Animaux',
                'Séries', 'Danse', 'Mode', 'Photographie', 'Fitness'
            ];
            foreach ($interests as $interest): ?>
                <div class="option-card">
                    <input type="checkbox" name="interests[]" value="<?= htmlspecialchars($interest) ?>" id="interest_<?= md5($interest) ?>">
                    <label for="interest_<?= md5($interest) ?>">
                        <p><?= htmlspecialchars($interest) ?></p>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="interests-suggestions">
            <span>Exemples&nbsp;: "Fan de sport et de voyages", "Passionné(e) de musique et de nature", "Toujours partant(e) pour un ciné ou une rando"...</span>
        </div>
        <button type="submit">Continuer</button>
    </div>
</form>

<style>
body, .onboarding-bg {
    background: #f8f9fa;
    min-height: 100vh;
}
.onboarding-card {
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
.interests-header {
    text-align: center;
    margin-bottom: 2rem;
}
.interests-header h2 {
    color: #6c5ce7;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
.interests-subtitle {
    color: #a29bfe;
    font-size: 1rem;
}
.option-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    padding: 1rem 0;
    width: 100%;
}
.option-card {
    position: relative;
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 0.5rem;
    background: #fff;
    box-shadow: none;
    padding: 0;
}
.option-card input[type="checkbox"] {
    position: absolute;
    opacity: 0;
}
.option-card label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    border: none;
    background: none;
    transition: all 0.3s ease;
}
.option-card input[type="checkbox"]:checked + label {
    border: 2px solid #6c5ce7 !important;
    background-color: #f3e8ff;
    box-shadow: 0 0 0 2px #a29bfe33;
}
.option-card:hover label {
    border-color: #a29bfe;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.option-card p {
    margin: 0;
    color: #4a5568;
    font-weight: 500;
    text-align: center;
}
.interests-suggestions {
    margin: 1.5rem 0 0 0;
    color: #6c5ce7;
    font-size: 0.98rem;
    text-align: center;
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
</style> 