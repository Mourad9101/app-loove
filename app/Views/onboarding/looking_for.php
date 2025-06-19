<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="option-grid">
        <div class="option-card" data-gem="diamond">
            <input type="radio" name="gemstone" value="Diamond" id="gem_diamond" required>
            <label for="gem_diamond">
                <!-- Diamant (inspiré Phosphor/Lucide) -->
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#6c5ce7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="24,6 6,18 24,42 42,18"/>
                  <polyline points="6,18 24,24 42,18"/>
                  <line x1="24" y1="6" x2="24" y2="42"/>
                  <line x1="12" y1="18" x2="24" y2="24"/>
                  <line x1="36" y1="18" x2="24" y2="24"/>
                </svg>
                <p>Diamant</p>
            </label>
        </div>
        <div class="option-card" data-gem="ruby">
            <input type="radio" name="gemstone" value="Ruby" id="gem_ruby">
            <label for="gem_ruby">
                <!-- Rubis (inspiré Lucide) -->
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#6c5ce7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="24,8 8,20 24,40 40,20"/>
                  <polyline points="8,20 24,24 40,20"/>
                  <line x1="24" y1="8" x2="24" y2="40"/>
                  <line x1="16" y1="20" x2="24" y2="24"/>
                  <line x1="32" y1="20" x2="24" y2="24"/>
                </svg>
                <p>Rubis</p>
            </label>
        </div>
        <div class="option-card" data-gem="emerald">
            <input type="radio" name="gemstone" value="Emerald" id="gem_emerald">
            <label for="gem_emerald">
                <!-- Émeraude (inspiré Lucide/Phosphor) -->
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#6c5ce7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="12" y="10" width="24" height="28" rx="7"/>
                  <line x1="12" y1="18" x2="36" y2="18"/>
                  <line x1="12" y1="30" x2="36" y2="30"/>
                  <line x1="24" y1="10" x2="24" y2="38"/>
                </svg>
                <p>Émeraude</p>
            </label>
        </div>
        <div class="option-card" data-gem="sapphire">
            <input type="radio" name="gemstone" value="Sapphire" id="gem_sapphire">
            <label for="gem_sapphire">
                <!-- Saphir (inspiré Lucide/Phosphor) -->
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#6c5ce7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <ellipse cx="24" cy="24" rx="14" ry="20"/>
                  <line x1="24" y1="4" x2="24" y2="44"/>
                  <line x1="10" y1="24" x2="38" y2="24"/>
                </svg>
                <p>Saphir</p>
            </label>
        </div>
        <div class="option-card" data-gem="amethyst">
            <input type="radio" name="gemstone" value="Amethyst" id="gem_amethyst">
            <label for="gem_amethyst">
                <!-- Améthyste (inspiré Lucide/Phosphor) -->
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#6c5ce7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="24,6 10,18 10,34 24,42 38,34 38,18"/>
                  <line x1="24" y1="6" x2="24" y2="42"/>
                  <line x1="10" y1="18" x2="38" y2="34"/>
                  <line x1="38" y1="18" x2="10" y2="34"/>
                </svg>
                <p>Améthyste</p>
            </label>
        </div>
    </div>
    <button type="submit">Continuer</button>
</form>

<style>
body, .onboarding-bg {
    background: #f8f9fa;
    min-height: 100vh;
}
.option-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    padding: 2rem 1rem 1rem 1rem;
    background: none;
    border-radius: 1.5rem;
    box-shadow: none;
    margin-bottom: 2rem;
}
.option-card {
    position: relative;
    cursor: pointer;
    border-radius: 1rem;
    transition: box-shadow 0.3s, transform 0.2s;
    box-shadow: 0 2px 8px rgba(108, 92, 231, 0.08);
    background: #fff;
    border: 2.5px solid #6c5ce7;
}
.option-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}
.option-card label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 1rem 1.5rem 1rem;
    border-radius: 1rem;
    background: #fff;
    font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
    transition: all 0.3s;
    border: none;
}
.option-card input[type="radio"]:checked + label {
    box-shadow: 0 0 0 4px #a29bfe55;
    background: #f8f9fa;
}
.option-card:hover label {
    box-shadow: 0 0 0 4px #a29bfe55;
    background: #f8f9fa;
    transform: translateY(-4px) scale(1.03);
}
.option-card svg {
    width: 48px;
    height: 48px;
    margin-bottom: 1rem;
    transition: all 0.3s;
    stroke: #6c5ce7;
}
.option-card p {
    margin: 0;
    color: #6c5ce7;
    font-weight: 600;
    font-size: 1.1rem;
    text-align: center;
    letter-spacing: 0.5px;
}
button[type="submit"] {
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
}
@media (max-width: 600px) {
    .option-grid { grid-template-columns: 1fr; gap: 1rem; padding: 1rem; }
    .option-card label { padding: 1.2rem 0.5rem; }
    button[type="submit"] { padding: 14px 18px; font-size: 16px; }
}
</style>

<script src="<?= base_url('js/onboarding.js') ?>"></script> 