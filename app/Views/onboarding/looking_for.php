<form method="POST" action="<?= BASE_URL ?>/onboarding">
    <div class="option-grid">
        <?php foreach ($options as $value => $label): ?>
            <div class="option-card" data-gem="<?= $value === 'relationship' ? 'diamond' : ($value === 'friendship' ? 'sapphire' : 'ruby') ?>">
                <input type="radio" name="looking_for" value="<?= $value ?>" id="looking_<?= $value ?>" required>
                <label for="looking_<?= $value ?>">
                    <?php if ($value === 'relationship'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-gem="diamond">
                            <!-- Diamant plus détaillé -->
                            <path d="M12 2L4 10l8 12 8-12-8-8z"/>
                            <path d="M12 2L8 10h8l-4-8z"/>
                            <path d="M4 10h16"/>
                            <path d="M8 10l4 12 4-12"/>
                            <path d="M7 7l5-5 5 5"/>
                        </svg>
                    <?php elseif ($value === 'friendship'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-gem="sapphire">
                            <!-- Saphir plus détaillé -->
                            <path d="M12 3L6 9l6 12 6-12-6-6z"/>
                            <path d="M12 3L9 9h6l-3-6z"/>
                            <path d="M6 9h12"/>
                            <path d="M9 9l3 12 3-12"/>
                            <path d="M8 6l4-3 4 3"/>
                            <!-- Facettes intérieures -->
                            <path d="M12 15L9 9l3-6 3 6-3 6z"/>
                        </svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-gem="ruby">
                            <!-- Rubis plus détaillé -->
                            <path d="M12 2L5 8l7 14 7-14-7-6z"/>
                            <path d="M12 2L8 8h8l-4-6z"/>
                            <path d="M5 8h14"/>
                            <path d="M8 8l4 14 4-14"/>
                            <!-- Facettes caractéristiques du rubis -->
                            <path d="M12 16L8 8l4-6 4 6-4 8z"/>
                            <path d="M9 11l3 5 3-5"/>
                        </svg>
                    <?php endif; ?>
                    <p style="margin-top: 10px;"><?= $label ?></p>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    
    <button type="submit" style="
        margin-top: 20px;
        padding: 15px 30px;
        background-color: #4299e1;
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
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
    width: 64px;  /* Increased size for better visibility */
    height: 64px;
    transition: all 0.3s ease;
}

/* Styles par défaut des pierres */
.option-card[data-gem="diamond"] svg {
    stroke: #B4C6D4;
}

.option-card[data-gem="sapphire"] svg {
    stroke: #2C5282;
}

.option-card[data-gem="ruby"] svg {
    stroke: #9B2C2C;
}

/* Styles au survol */
.option-card[data-gem="diamond"]:hover svg {
    stroke: #63B3ED;
    filter: drop-shadow(0 0 4px rgba(99, 179, 237, 0.6));
}

.option-card[data-gem="sapphire"]:hover svg {
    stroke: #4299E1;
    filter: drop-shadow(0 0 4px rgba(66, 153, 225, 0.6));
}

.option-card[data-gem="ruby"]:hover svg {
    stroke: #F56565;
    filter: drop-shadow(0 0 4px rgba(245, 101, 101, 0.6));
}

/* Styles quand sélectionné */
.option-card[data-gem="diamond"] input[type="radio"]:checked + label svg {
    stroke: #63B3ED;
    filter: drop-shadow(0 0 6px rgba(99, 179, 237, 0.8));
}

.option-card[data-gem="sapphire"] input[type="radio"]:checked + label svg {
    stroke: #4299E1;
    filter: drop-shadow(0 0 6px rgba(66, 153, 225, 0.8));
}

.option-card[data-gem="ruby"] input[type="radio"]:checked + label svg {
    stroke: #F56565;
    filter: drop-shadow(0 0 6px rgba(245, 101, 101, 0.8));
}

/* Animation de brillance */
@keyframes sparkle {
    0% { filter: drop-shadow(0 0 3px currentColor); }
    50% { filter: drop-shadow(0 0 6px currentColor); }
    100% { filter: drop-shadow(0 0 3px currentColor); }
}

.option-card input[type="radio"]:checked + label svg {
    animation: sparkle 2s infinite;
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

<script src="<?= base_url('js/onboarding.js') ?>"></script> 