<form method="POST" action="<?= BASE_URL ?>/onboarding" id="bioForm">
        <div class="bio-input-container">
            <textarea 
                name="bio" 
                id="bioText" 
                placeholder="Je suis passionné(e) par..."
                maxlength="500"
            ></textarea>
            <div class="bio-counter">
                <span id="charCount">0</span>
                <span>/</span>
                <span>500</span>
            </div>
        </div>
    
        <div class="bio-suggestions">
            <h3>Suggestions pour votre bio :</h3>
            <ul>
                <li>Partagez vos passions</li>
                <li>Montrez votre personnalité</li>
                <li>Parlez de ce que vous recherchez</li>
            </ul>
        </div>
    
    <button type="submit" id="submitBio" class="next-button" disabled>Continuer</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bioForm');
    const bioText = document.getElementById('bioText');
    const submitButton = document.getElementById('submitBio');
    const charCount = document.getElementById('charCount');
    const minLength = 30;

    function updateCounter() {
        const currentLength = bioText.value.trim().length;
        charCount.textContent = currentLength;
        submitButton.disabled = currentLength < minLength;
        if (currentLength >= 400) {
            charCount.style.color = '#e53e3e';
        } else {
            charCount.style.color = '#6c5ce7';
        }
    }
    bioText.addEventListener('input', updateCounter);
    updateCounter();
});
</script> 

<script src="<?= BASE_URL ?>/js/onboarding.js"></script> 