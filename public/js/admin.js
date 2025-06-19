const BASE_URL = window.BASE_URL || '';

function showConfirmToast(message, onConfirm) {
    // Fermer tous les toasts existants
    document.querySelectorAll('.toastify').forEach(t => t.remove());

    const toast = document.createElement('div');
    toast.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;">
            <span style="margin-bottom:10px;">${message}</span>
            <div>
                <button id="toast-yes" style="margin-right:10px;background:#4299e1;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;">Oui</button>
                <button id="toast-no" style="background:#e53e3e;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;">Non</button>
            </div>
        </div>
    `;
    const toastify = Toastify({
        node: toast,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "center",
        stopOnFocus: true,
        style: { background: "#fff", color: "#222", boxShadow: "0 2px 8px #0002", borderRadius: "10px" }
    });
    toastify.showToast();
    toast.querySelector('#toast-yes').onclick = function() {
        onConfirm();
        toastify.hideToast();
    };
    toast.querySelector('#toast-no').onclick = function() {
        toastify.hideToast();
    };
}

function showSuccessToast(message) {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "center",
        backgroundColor: "#38a169",
        style: { color: "#fff", borderRadius: "8px", fontWeight: "bold" }
    }).showToast();
}

function showErrorToast(message) {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "center",
        backgroundColor: "#e53e3e",
        style: { color: "#fff", borderRadius: "8px", fontWeight: "bold" }
    }).showToast();
}

function toggleUserStatus(userId, currentStatus) {
    const action = currentStatus ? 'désactiver' : 'activer';
    showConfirmToast(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`, function() {
        doToggleUserStatus(userId, currentStatus);
    });
}

function doToggleUserStatus(userId, currentStatus) {
    const action = currentStatus ? 'désactiver' : 'activer';
    fetch(`${BASE_URL}/admin/user/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_active: !currentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(`Utilisateur ${action === 'désactiver' ? 'désactivé' : 'activé'} avec succès.`);
            setTimeout(() => location.reload(), 1200);
        } else {
            showErrorToast(data.error || 'Une erreur est survenue lors de la mise à jour du statut.');
        }
    })
    .catch(error => {
        showErrorToast('Erreur de communication avec le serveur.');
    });
}

function togglePremiumStatus(userId, currentStatus) {
    const action = currentStatus ? 'rétrograder premium' : 'promouvoir premium';
    showConfirmToast(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`, function() {
        doTogglePremiumStatus(userId, currentStatus);
    });
}

function doTogglePremiumStatus(userId, currentStatus) {
    const action = currentStatus ? 'rétrograder premium' : 'promouvoir premium';
    fetch(`${BASE_URL}/admin/user/${userId}/toggle-premium-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_premium: !currentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(`Utilisateur ${action === 'rétrograder premium' ? 'rétrogradé premium' : 'promu premium'} avec succès.`);
            setTimeout(() => location.reload(), 1200);
        } else {
            showErrorToast(data.error || 'Une erreur est survenue lors de la mise à jour du statut premium.');
        }
    })
    .catch(error => {
        showErrorToast('Erreur de communication avec le serveur.');
    });
}

function toggleAdminStatus(userId, currentStatus) {
    const action = currentStatus ? 'rétrograder' : 'promouvoir en admin';
    showConfirmToast(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`, function() {
        doToggleAdminStatus(userId, currentStatus);
    });
}

function doToggleAdminStatus(userId, currentStatus) {
    const action = currentStatus ? 'rétrograder' : 'promouvoir en admin';
    fetch(`${BASE_URL}/admin/user/${userId}/toggle-admin-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_admin: !currentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(`Utilisateur ${action === 'rétrograder' ? 'rétrogradé' : 'promu administrateur'} avec succès.`);
            setTimeout(() => location.reload(), 1200);
        } else {
            showErrorToast(data.error || 'Une erreur est survenue lors de la mise à jour du statut d\'administrateur.');
        }
    })
    .catch(error => {
        showErrorToast('Erreur de communication avec le serveur.');
    });
}

function deleteUser(userId) {
    showConfirmToast('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ? Cette action est irréversible.', function() {
        doDeleteUser(userId);
    });
}

function doDeleteUser(userId) {
    fetch(`${BASE_URL}/admin/user/${userId}/delete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast('Utilisateur supprimé avec succès.');
            setTimeout(() => location.reload(), 1200);
        } else {
            showErrorToast(data.error || 'Une erreur est survenue lors de la suppression de l\'utilisateur.');
        }
    })
    .catch(error => {
        showErrorToast('Erreur de communication avec le serveur.');
    });
} 