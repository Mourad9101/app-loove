<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="option-grid">
        <?php foreach ($options as $value => $label): ?>
            <div class="option-card">
                <input type="radio" name="looking_for" value="<?= $value ?>" id="looking_for_<?= $value ?>" required>
                <label for="looking_for_<?= $value ?>">
                    <p><?= $label ?></p>
            </label>
        </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="next-button">Continuer</button>
</form>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 