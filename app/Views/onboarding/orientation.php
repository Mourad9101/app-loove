<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="option-grid">
        <?php foreach ($options as $value => $label): ?>
            <div class="option-card">
                <input type="radio" name="orientation" value="<?= $value ?>" id="orientation_<?= $value ?>" required>
                <label for="orientation_<?= $value ?>">
                    <?php if ($value === 'heterosexual'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            <path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/>
                        </svg>
                    <?php elseif ($value === 'homosexual'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v8M8 12h8"/>
                        </svg>
                    <?php elseif ($value === 'bisexual'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M2 12h20"/>
                        </svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                    <?php endif; ?>
                    <p style="margin-top: 10px;"><?= $label ?></p>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    
    <button type="submit" style="
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #4299e1;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
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

.option-card input[type="radio"]:checked + label {
    border-color: #4299e1;
    background-color: #ebf8ff;
}

.option-card:hover label {
    border-color: #4299e1;
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

button[type="submit"] {
    display: block;
    margin: 20px auto;
    padding: 15px 30px;
    background-color: #4299e1;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #3182ce;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style> 