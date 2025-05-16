<form method="POST" action="<?= BASE_URL ?>/onboarding" id="bioForm">
    <div class="bio-container">
        <div class="bio-wrapper">
            <div class="bio-header">
                <h2>Parlez-nous de vous</h2>
                <p class="bio-subtitle">Votre bio est votre chance de briller ! Partagez ce qui vous rend unique.</p>
            </div>
            
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
                <div class="bio-suggestions">
                    <h3>Suggestions pour votre bio :</h3>
                    <ul>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            Partagez vos passions
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                <line x1="15" y1="9" x2="15.01" y2="9"></line>
                            </svg>
                            Montrez votre personnalité
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                            Parlez de ce que vous recherchez
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" id="submitBio" disabled>Continuer</button>
</form>

<style>
.bio-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.bio-wrapper {
    background-color: #ffffff;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.bio-header {
    text-align: center;
    margin-bottom: 2rem;
}

.bio-header h2 {
    color: #2d3748;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.bio-subtitle {
    color: #718096;
    font-size: 0.875rem;
}

.bio-input-container {
    position: relative;
    margin-bottom: 2rem;
}

textarea {
    width: 100%;
    min-height: 150px;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    font-size: 1rem;
    color: #4a5568;
    resize: vertical;
    transition: all 0.3s ease;
}

textarea:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
}

.bio-counter {
    position: absolute;
    bottom: -1.5rem;
    right: 0;
    font-size: 0.75rem;
    color: #718096;
}

.bio-suggestions {
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.bio-suggestions h3 {
    color: #4a5568;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.bio-suggestions ul {
    list-style: none;
    padding: 0;
}

.bio-suggestions li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.bio-suggestions svg {
    color: #4299e1;
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

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.bio-suggestions li {
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
    opacity: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bioForm');
    const bioText = document.getElementById('bioText');
    const submitButton = document.getElementById('submitBio');
    const charCount = document.getElementById('charCount');
    const minLength = 50;

    function updateCounter() {
        const currentLength = bioText.value.trim().length;
        charCount.textContent = currentLength;
        
        // Active le bouton si la bio a au moins 50 caractères
        submitButton.disabled = currentLength < minLength;
        
        // Change la couleur du compteur selon la longueur
        if (currentLength >= 400) {
            charCount.style.color = '#e53e3e';
        } else if (currentLength >= 300) {
            charCount.style.color = '#dd6b20';
        } else {
            charCount.style.color = '#718096';
        }
    }

    bioText.addEventListener('input', updateCounter);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const bioLength = bioText.value.trim().length;
        if (bioLength < minLength) {
            alert(`Votre bio doit contenir au moins ${minLength} caractères.`);
            return;
        }

        this.submit();
    });

    // Animation des suggestions
    const suggestions = document.querySelectorAll('.bio-suggestions li');
    suggestions.forEach((suggestion, index) => {
        suggestion.style.setProperty('--animation-order', index);
    });

    // Initialisation du compteur
    updateCounter();
});
</script> 