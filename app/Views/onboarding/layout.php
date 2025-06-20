<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - EverGem</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        .onboarding-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .progressbar-container {
            width: 100%;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .progressbar-label {
            font-size: 1rem;
            color: #6c5ce7;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .progress-bar {
            width: 100%;
            max-width: 420px;
            height: 14px;
            background: #ecebfa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(108,92,231,0.08);
            position: relative;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #6c5ce7 0%, #a29bfe 100%);
            width: <?= ($progress['current'] / $progress['total']) * 100 ?>%;
            border-radius: 8px 0 0 8px;
            transition: width 0.5s cubic-bezier(.4,1.4,.6,1);
            box-shadow: 0 2px 8px #a29bfe33;
        }

        .step-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .step-description {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .onboarding-form {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .option-card {
            background: #fff;
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .option-card:hover {
            border-color: #6c5ce7;
            transform: translateY(-2px);
        }

        .option-card.selected {
            border-color: #6c5ce7;
            background: rgba(108, 92, 231, 0.05);
        }

        .next-button {
            width: 100%;
            padding: 15px;
            background: #6c5ce7;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease;
            margin-top: auto;
        }

        .next-button:hover {
            transform: translateY(-2px);
        }

        .next-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Styles spécifiques pour l'upload de photos */
        .photo-upload-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 30px;
        }

        .photo-upload-box {
            aspect-ratio: 1;
            background: #f8f8f8;
            border: 2px dashed #ddd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .photo-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-upload-box input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Style pour les intérêts */
        .interest-tag {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            background: #f8f8f8;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .interest-tag.selected {
            background: #6c5ce7;
            color: white;
        }

        /* Style pour la bio */
        .bio-textarea {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 12px;
            resize: none;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .bio-textarea:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        /* Style pour les icônes SVG */
        .option-card svg {
            width: 32px;
            height: 32px;
            margin-bottom: 10px;
            stroke: #666;
            transition: stroke 0.2s ease;
        }

        .option-card.selected svg {
            stroke: #6c5ce7;
        }

        body, .onboarding-bg {
            background: #f8f9fa;
            min-height: 100vh;
        }

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
    </style>
</head>
<body>
    <div class="onboarding-container">
        <div class="progressbar-container">
            <div class="progressbar-label">
                <?= round(($progress['current'] / $progress['total']) * 100) ?>% complété
            </div>
            <div class="progress-bar">
                <div class="progress-bar-fill"></div>
            </div>
        </div>

        <h1 class="step-title"><?= $title ?></h1>
        <?php if (isset($description)): ?>
            <p class="step-description"><?= $description ?></p>
        <?php endif; ?>

        <?= $content ?>
    </div>

    <script>
        // Fonction pour gérer les sélections de cartes
        function initializeOptionCards() {
            document.querySelectorAll('.option-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.querySelector('input');
                    if (!input) return;

                    if (input.type === 'radio') {
                        // Pour les boutons radio, désélectionner tous les autres
                        document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                        this.classList.add('selected');
                        input.checked = true;
                    } else if (input.type === 'checkbox') {
                        // Pour les checkboxes, toggle la sélection
                        this.classList.toggle('selected');
                        input.checked = !input.checked;
                        
                        // Si c'est pour les intérêts, vérifier le minimum
                        if (document.querySelector('.interest-tag')) {
                            const selectedCount = document.querySelectorAll('.option-card.selected').length;
                            document.querySelector('.next-button').disabled = selectedCount < 3;
                        }
                    }
                });
            });
        }

        // Fonction pour gérer les tags d'intérêts
        function initializeInterestTags() {
            document.querySelectorAll('.interest-tag').forEach(tag => {
                tag.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.querySelector('input');
                    if (!input) return;

                    this.classList.toggle('selected');
                    input.checked = !input.checked;

                    const selectedCount = document.querySelectorAll('.interest-tag.selected').length;
                    document.querySelector('.next-button').disabled = selectedCount < 3;
                });
            });
        }

        // Fonction pour gérer l'upload de photos
        function initializePhotoUpload() {
            document.querySelectorAll('.photo-upload-box input[type="file"]').forEach(input => {
                input.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        const box = this.parentElement;
                        
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            box.innerHTML = '';
                            box.appendChild(img);
                            box.appendChild(input);
                        }
                        
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
        }

        // Fonction pour gérer le compteur de caractères de la bio
        function initializeBioCounter() {
            const textarea = document.querySelector('.bio-textarea');
            if (textarea) {
                const charCount = document.querySelector('#charCount');
                textarea.addEventListener('input', function() {
                    const length = this.value.length;
                    if (charCount) charCount.textContent = length;
                    document.querySelector('.next-button').disabled = length < 50;
                });
            }
        }

        // Initialiser toutes les fonctionnalités
        document.addEventListener('DOMContentLoaded', function() {
            initializeOptionCards();
            initializeInterestTags();
            initializePhotoUpload();
            initializeBioCounter();
        });
    </script>
</body>
</html> 