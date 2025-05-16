<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div style="max-width: 300px; margin: 0 auto;">
        <input type="date" 
               name="birth_date" 
               required 
               max="<?= date('Y-m-d', strtotime('-18 years')) ?>"
               style="width: 100%;
                      padding: 15px;
                      border: 2px solid #e2e8f0;
                      border-radius: 12px;
                      font-size: 16px;
                      margin-bottom: 20px;
                      cursor: pointer;
                      transition: all 0.3s ease;">
                      
        <p class="step-description" style="
            text-align: center;
            color: #4a5568;
            margin-bottom: 20px;
        ">
            Vous devez avoir au moins 18 ans pour utiliser EverGem.
        </p>

        <button type="submit" style="
            width: 100%;
            padding: 15px 20px;
            background-color: #4299e1;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        ">Continuer</button>
    </div>
</form>

<style>
input[type="date"]:hover {
    border-color: #4299e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

input[type="date"]:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
}

button[type="submit"]:hover {
    background-color: #3182ce;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>

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