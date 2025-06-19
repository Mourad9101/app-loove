<form method="POST" action="<?= BASE_URL ?>/onboarding" id="bioForm">
    <div class="onboarding-card">
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
        </div>
        <div class="bio-suggestions">
            <h3>Suggestions pour votre bio :</h3>
            <ul>
                <li>Partagez vos passions</li>
                <li>Montrez votre personnalité</li>
                <li>Parlez de ce que vous recherchez</li>
            </ul>
        </div>
        <button type="submit" id="submitBio" disabled>Continuer</button>
    </div>
</form>

<style>
body, .onboarding-bg {
    background: #f8f9fa;
    min-height: 100vh;
}
.onboarding-card {
    max-width: 500px;
    margin: 40px auto 0 auto;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 4px 16px rgba(108, 92, 231, 0.10);
    padding: 2.5rem 2rem 2rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.bio-header {
    text-align: center;
    margin-bottom: 2rem;
}
.bio-header h2 {
    color: #6c5ce7;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
.bio-subtitle {
    color: #6c5ce7;
    font-size: 1rem;
}
.bio-input-container {
    width: 100%;
    position: relative;
    margin-bottom: 1.5rem;
}
textarea {
    width: 100%;
    min-height: 120px;
    padding: 1rem;
    border: 2px solid #6c5ce7;
    border-radius: 1rem;
    font-size: 1rem;
    color: #4a5568;
    resize: vertical;
    transition: all 0.3s ease;
    background: #f3e8ff;
    font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
}
textarea:focus {
    outline: none;
    border-color: #6c5ce7;
    box-shadow: 0 0 0 3px #6c5ce766;
}
.bio-counter {
    position: absolute;
    bottom: -1.5rem;
    right: 0;
    font-size: 0.85rem;
    color: #6c5ce7;
}
.bio-suggestions {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e9d8fd;
    width: 100%;
}
.bio-suggestions h3 {
    color: #6c5ce7;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    text-align: center;
}
.bio-suggestions ul {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: center;
}
.bio-suggestions li {
    color: #6c5ce7;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    display: inline-block;
    margin-right: 1.2rem;
}
.bio-suggestions li:last-child { margin-right: 0; }
button[type="submit"] {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #6c5ce7;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}
button[type="submit"]:hover:not(:disabled) {
    background-color: #a29bfe;
}
button[type="submit"]:disabled {
    background-color: #cbd5e0;
    cursor: not-allowed;
    opacity: 0.7;
}
@media (max-width: 600px) {
    .onboarding-card { padding: 1.2rem 0.5rem; }
    button[type="submit"] { padding: 14px 18px; font-size: 16px; }
}
</style>

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