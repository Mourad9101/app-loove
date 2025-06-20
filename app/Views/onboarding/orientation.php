<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="option-grid">
        <?php foreach ($options as $value => $label): ?>
            <div class="option-card">
                <input type="radio" name="orientation" value="<?= $value ?>" id="orientation_<?= $value ?>" required>
                <label for="orientation_<?= $value ?>">
                    <p><?= $label ?></p>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    
    <button type="submit" class="next-button">Continuer</button>
</form>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script>