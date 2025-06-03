<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Messages</h1>
    <div class="row">
         <div class="col-md-4">
             <div class="card">
                 <div class="card-header">
                     Conversations
                 </div>
                 <div class="card-body" id="conversations-list">
                     <!-- La liste des conversations sera chargée ici (par exemple, via une boucle sur $conversations) -->
                     <?php if (isset($conversations) && !empty($conversations)) : ?>
                         <?php foreach ($conversations as $conv) : ?>
                             <div class="conversation-item mb-2 p-2 border rounded" data-conv-id="<?= htmlspecialchars($conv['id']) ?>">
                                 <h5><?= htmlspecialchars($conv['recipient_name']) ?></h5>
                                 <small class="text-muted"><?= htmlspecialchars($conv['last_message'] ?? 'Aucun message') ?></small>
                             </div>
                         <?php endforeach; ?>
                     <?php else : ?>
                         <p class="text-muted">Aucune conversation.</p>
                     <?php endif; ?>
                 </div>
             </div>
         </div>
         <div class="col-md-8">
             <div class="card">
                 <div class="card-header" id="chat-header">
                     <!-- Le nom du destinataire (ou un titre par défaut) sera affiché ici -->
                     Conversation
                 </div>
                 <div class="card-body" id="chat-messages" style="height: 400px; overflow-y: auto;">
                     <!-- Les messages de la conversation sélectionnée seront chargés ici -->
                     <p class="text-muted">Sélectionnez une conversation pour voir les messages.</p>
                 </div>
                 <div class="card-footer">
                     <form id="send-message-form" class="d-flex" style="display: none;">
                         <input type="text" class="form-control me-2" id="message-input" placeholder="Écrivez votre message..." />
                         <button type="submit" class="btn btn-primary">Envoyer</button>
                     </form>
                 </div>
             </div>
         </div>
    </div>
</div>

<style>
 .conversation-item:hover { background-color: #f8f9fa; cursor: pointer; }
</style>

<?php require_once APP_PATH . '/Views/layout/footer.php'; ?> 