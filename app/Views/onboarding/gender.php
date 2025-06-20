<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="option-grid">
        <?php foreach ($options as $value => $label): ?>
            <div class="option-card">
                <input type="radio" name="gender" value="<?= $value ?>" id="gender_<?= $value ?>" required>
                <label for="gender_<?= $value ?>">
                    <?php if ($value === 'H'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="7"/>
                            <path d="M12 19v3M12 2v3M19 12h3M2 12h3"/>
                        </svg>
                    <?php elseif ($value === 'F'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="7"/>
                            <path d="M12 15v7M8 21h8"/>
                        </svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v8M8 12h8"/>
                        </svg>
                    <?php endif; ?>
                    <p style="margin-top: 10px;"><?= $label ?></p>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    
    <button type="submit" class="next-button">Continuer</button>
</form>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 