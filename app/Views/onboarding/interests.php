<form method="POST" action="<?= BASE_URL ?>/onboarding" id="interestsForm">
    <div class="interests-container">
        <?php foreach ($interests as $value => $label): ?>
            <div class="interest-tag" data-value="<?= $value ?>">
                <input type="checkbox" name="interests[]" value="<?= $value ?>" id="interest_<?= $value ?>">
                <label for="interest_<?= $value ?>">
                    <?php
                    $icon = '';
                    switch($value) {
                        case 'sport':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2v20M2 12h20"/></svg>';
                            break;
                        case 'music':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>';
                            break;
                        case 'cinema':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="2" y1="7" x2="7" y2="7"/><line x1="2" y1="17" x2="7" y2="17"/><line x1="17" y1="17" x2="22" y2="17"/><line x1="17" y1="7" x2="22" y2="7"/></svg>';
                            break;
                        case 'reading':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>';
                            break;
                        case 'travel':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.38 3.46L16 2l-4 8-4-8-4.38 1.46a1 1 0 0 0-.62.92v16.62a1 1 0 0 0 1.38.92L8 20l4 2 4-2 3.62 1.92a1 1 0 0 0 1.38-.92V4.38a1 1 0 0 0-.62-.92z"/></svg>';
                            break;
                        case 'cooking':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" y1="17" x2="18" y2="17"/></svg>';
                            break;
                        case 'art':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>';
                            break;
                        case 'gaming':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="17" y1="11" x2="17.01" y2="11"/><path d="M2 6l.01 12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2L22 6H2z"/></svg>';
                            break;
                        case 'technology':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>';
                            break;
                        case 'nature':
                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
                            break;
                    }
                    ?>
                    <span class="interest-icon"><?= $icon ?></span>
                    <span class="interest-label"><?= $label ?></span>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <p class="step-description">
        Sélectionnez au moins 3 centres d'intérêt pour continuer
        <span class="interest-counter">0/3</span>
    </p>

    <button type="submit" id="submitInterests" disabled>Continuer</button>
</form>

<style>
.interests-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 1rem;
}

.interest-tag {
    position: relative;
}

.interest-tag input[type="checkbox"] {
    display: none;
}

.interest-tag label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background-color: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 9999px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    color: #4a5568;
    white-space: nowrap;
}

.interest-tag:hover label {
    border-color: #4299e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.interest-tag input[type="checkbox"]:checked + label {
    background-color: #ebf8ff;
    border-color: #4299e1;
    color: #2b6cb0;
}

.interest-icon {
    display: flex;
    align-items: center;
}

.interest-icon svg {
    stroke: currentColor;
}

.step-description {
    text-align: center;
    color: #4a5568;
    margin: 2rem 0;
    font-size: 0.875rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.interest-counter {
    font-weight: 500;
    color: #2b6cb0;
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

button[type="submit"]:hover:not(:disabled) {
    background-color: #3182ce;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

button[type="submit"]:disabled {
    background-color: #cbd5e0;
    cursor: not-allowed;
    opacity: 0.7;
}

@keyframes pop {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.interest-tag input[type="checkbox"]:checked + label {
    animation: pop 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('interestsForm');
    const submitButton = document.getElementById('submitInterests');
    const interestTags = document.querySelectorAll('.interest-tag input[type="checkbox"]');
    const counter = document.querySelector('.interest-counter');
    const minRequired = 3;

    function updateCounter() {
        const selectedCount = document.querySelectorAll('.interest-tag input[type="checkbox"]:checked').length;
        counter.textContent = `${selectedCount}/${minRequired}`;
        submitButton.disabled = selectedCount < minRequired;
    }

    interestTags.forEach(tag => {
        tag.addEventListener('change', updateCounter);
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedCount = document.querySelectorAll('.interest-tag input[type="checkbox"]:checked').length;
        if (selectedCount < minRequired) {
            alert(`Veuillez sélectionner au moins ${minRequired} centres d'intérêt pour continuer.`);
            return;
        }

        this.submit();
    });

    // Initialisation du compteur
    updateCounter();
});
</script> 