<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <!-- Image principale -->
                <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image']) ?>" 
                     class="card-img-top" 
                     alt="Photo de profil"
                     onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                
                <!-- Images supplémentaires -->
                <?php if (!empty($user['additional_images'])): ?>
                    <?php 
                    $additionalImages = json_decode($user['additional_images'], true);
                    if (is_array($additionalImages) && !empty($additionalImages)):
                    ?>
                        <div class="additional-images mt-2">
                            <div class="row">
                                <?php foreach ($additionalImages as $index => $image): ?>
                                    <div class="col-4 mb-2">
                                        <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($image) ?>" 
                                             class="img-fluid rounded" 
                                             alt="Photo supplémentaire <?= $index + 1 ?>"
                                             style="width: 100%; height: 80px; object-fit: cover;"
                                             onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?>
                        <?php if ($user['is_premium']): ?>
                            <span class="premium-badge">
                                <i class="fas fa-crown"></i> Premium
                            </span>
                        <?php endif; ?>
                    </h5>
                    <p class="card-text">
                        <i class="fas fa-gem"></i> <?= htmlspecialchars($user['gemstone']) ?>
                    </p>
                </div>
            </div>
            <div class="mt-3">
                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary btn-block">
                    <i class="fas fa-edit"></i> Modifier mon profil
                </a>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informations personnelles</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-map-marker-alt"></i> Ville:</strong> <?= htmlspecialchars($user['city']) ?></p>
                            <p><strong><i class="fas fa-birthday-cake"></i> Âge:</strong> <?= htmlspecialchars($user['age']) ?> ans</p>
                            <p><strong><i class="fas fa-venus-mars"></i> Genre:</strong> 
                                <?php
                                $genderMap = ['H' => 'Homme', 'F' => 'Femme', 'NB' => 'Non-binaire'];
                                echo htmlspecialchars($genderMap[$user['gender']] ?? $user['gender']);
                                ?>
                            </p>
                            <p><strong><i class="fas fa-crown"></i> Abonnement:</strong> 
                                <?php if ($user['is_premium']): ?>
                                    <span class="premium-status">Premium</span>
                                <?php else: ?>
                                    <span class="free-status">Gratuit</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <h4 class="mt-4">À propos de moi</h4>
                    <hr>
                    <p><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div> 