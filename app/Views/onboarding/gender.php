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
    
    <button type="submit" style="
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
    ">Continuer</button>
</form>

<style>
.option-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    padding: 1rem;
}

.option-card {
    position: relative;
    cursor: pointer;
}

.option-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.option-card label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.option-card input[type="radio"]:checked + label, .gender-option.selected {
    border-color: #6c5ce7;
    background-color: #f3e8ff;
}

.option-card:hover label {
    border-color: #a29bfe;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.option-card svg {
    width: 40px;
    height: 40px;
    stroke: #4a5568;
    transition: all 0.3s ease;
}

.option-card input[type="radio"]:checked + label svg {
    stroke: #4299e1;
}

.option-card p {
    margin: 0;
    color: #4a5568;
    font-weight: 500;
    text-align: center;
}

body, .onboarding-bg {
    background: #f8f9fa;
    min-height: 100vh;
}

button[type="submit"] {
    background-color: #6c5ce7;
}

button[type="submit"]:hover:not(:disabled) {
    background-color: #a29bfe;
}
</style>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 