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
        <button type="submit" class="next-button">Continuer</button>
    </div>
</form>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 