<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Vos Conversations</h1>
    <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-header">
                     Conversations Actives
                 </div>
                 <div class="card-body" id="conversations-list">
                     <?php if (isset($conversations) && !empty($conversations)) : ?>
                         <?php foreach ($conversations as $conv) : ?>
                             <div class="conversation-item mb-2 p-2 border rounded" data-conv-id="<?= htmlspecialchars($conv['id'] ?? '') ?>">
                                 <h5><a href="<?= BASE_URL ?>/messages/<?= htmlspecialchars($conv['recipient_id'] ?? '') ?>"><?= htmlspecialchars($conv['recipient_name'] ?? '') ?></a></h5>
                                 <small class="text-muted"><?= htmlspecialchars($conv['last_message'] ?? 'Aucun message') ?></small>
                             </div>
                         <?php endforeach; ?>
                     <?php else : ?>
                         <p class="text-muted">Aucune conversation active pour le moment.</p>
                         <a href="<?= BASE_URL ?>/discover" class="btn btn-primary">Découvrir des profils</a>
                     <?php endif; ?>
                 </div>
             </div>
         </div>
    </div>
</div>

<style>
 .conversation-item:hover { background-color: #f8ff; cursor: pointer; }
</style>

