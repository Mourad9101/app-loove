document.addEventListener('DOMContentLoaded', function() {
    console.log('Script onboarding.js chargé');
    
    // Sélectionner tous les labels avec la classe option-card
    const optionCards = document.querySelectorAll('.option-card');
    console.log('Nombre de cartes trouvées:', optionCards.length);
    
    const form = document.querySelector('form');
    console.log('Formulaire trouvé:', form ? 'oui' : 'non');

    // Ajouter des écouteurs d'événements pour chaque carte
    optionCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    });
}); 