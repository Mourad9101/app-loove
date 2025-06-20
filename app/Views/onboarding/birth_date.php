<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="date-input-container">
        <input type="date" 
               name="birth_date" 
               required 
               max="<?= date('Y-m-d', strtotime('-18 years')) ?>"
               class="date-input">
                      
        <p class="step-description">
            Vous devez avoir au moins 18 ans pour utiliser EverGem.
        </p>

        <button type="submit" class="next-button">Continuer</button>
    </div>
</form>

<script>
    const dateInput = document.querySelector('input[type="date"]');
    const maxDate = new Date();
    maxDate.setFullYear(maxDate.getFullYear() - 18);
    
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        if (selectedDate > maxDate) {
            alert('Vous devez avoir au moins 18 ans pour utiliser EverGem.');
            this.value = '';
        }
    });

    // Définir la date maximale dans l'attribut max
    const maxDateStr = maxDate.toISOString().split('T')[0];
    dateInput.setAttribute('max', maxDateStr);
</script>

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 