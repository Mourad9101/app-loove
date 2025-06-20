document.addEventListener('DOMContentLoaded', function() {

    // S'assurer que les variables globales sont disponibles
    if (typeof BASE_URL === 'undefined') {
        console.error('La variable globale BASE_URL n\'est pas définie.');
        return;
    }

    if (typeof Toastify === 'undefined') {
        console.error('La librairie Toastify n\'est pas chargée.');
        return;
    }

    // --- Fonctions Toast --- //

    function showConfirmToast(message, onConfirm) {
        document.querySelectorAll('.toastify').forEach(t => t.remove());

        const toastNode = document.createElement('div');
        toastNode.innerHTML = `
            <div style="display:flex;flex-direction:column;align-items:center;text-align:center;width:100%;">
                <span style="margin-bottom:15px;font-size:1.1em;">${message}</span>
                <div style="display:flex;justify-content:center;width:100%;">
                    <button id="toast-yes" class="toast-button yes">Oui</button>
                    <button id="toast-no" class="toast-button no">Non</button>
                </div>
            </div>
            <style>
                .toast-button { margin: 0 10px; border:none; padding: 8px 18px; border-radius: 6px; cursor: pointer; font-weight: bold; }
                .toast-button.yes { background:#4299e1; color:#fff; }
                .toast-button.no { background:#e53e3e; color:#fff; }
            </style>
        `;

        const toast = Toastify({
            node: toastNode,
            duration: -1,
            close: true,
            gravity: "top",
            position: "center",
            stopOnFocus: true,
            style: { background: "#fff", color: "#222", boxShadow: "0 4px 12px #0003", borderRadius: "10px" }
        });

        toastNode.querySelector('#toast-yes').onclick = () => {
            onConfirm();
            toast.hideToast();
        };
        toastNode.querySelector('#toast-no').onclick = () => toast.hideToast();
        
        toast.showToast();
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
    
    // --- Fonctions d'API --- //
    
    function sendRequest(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(body)
        })
        .then(response => {
            if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
            return response.json();
        });
    }

    // --- Actions sur les utilisateurs --- //

    window.toggleUserStatus = function(userId, currentStatus) {
        const action = currentStatus ? 'désactiver' : 'activer';
        showConfirmToast(`Voulez-vous vraiment ${action} cet utilisateur ?`, () => {
            sendRequest(`${BASE_URL}/admin/user/${userId}/toggle-status`, { is_active: !currentStatus })
                .then(data => {
                    if (data.success) {
                        showSuccessToast(`Utilisateur ${action === 'désactiver' ? 'désactivé' : 'activé'}.`);
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showErrorToast(data.error || 'Erreur lors de la mise à jour.');
                    }
                })
                .catch(err => showErrorToast('Erreur de communication.'));
        });
    };

    window.togglePremiumStatus = function(userId, currentStatus) {
        const action = currentStatus ? 'rétrograder' : 'promouvoir premium';
        showConfirmToast(`Voulez-vous vraiment ${action} cet utilisateur ?`, () => {
            sendRequest(`${BASE_URL}/admin/user/${userId}/toggle-premium-status`, { is_premium: !currentStatus })
                .then(data => {
                    if (data.success) {
                        showSuccessToast(`Utilisateur ${action === 'rétrograder' ? 'rétrogradé' : 'promu premium'}.`);
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showErrorToast(data.error || 'Erreur lors de la mise à jour.');
                    }
                })
                .catch(err => showErrorToast('Erreur de communication.'));
        });
    };
    
    window.toggleAdminStatus = function(userId, currentStatus) {
        const action = currentStatus ? 'rétrograder' : 'promouvoir admin';
        showConfirmToast(`Voulez-vous vraiment ${action} cet utilisateur ?`, () => {
            sendRequest(`${BASE_URL}/admin/user/${userId}/toggle-admin-status`, { is_admin: !currentStatus })
                .then(data => {
                    if (data.success) {
                        showSuccessToast(`Utilisateur ${action === 'rétrograder' ? 'rétrogradé' : 'promu admin'}.`);
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showErrorToast(data.error || 'Erreur lors de la mise à jour.');
                    }
                })
                .catch(err => showErrorToast('Erreur de communication.'));
        });
    };
    
    window.deleteUser = function(userId) {
        showConfirmToast('Supprimer cet utilisateur ?<br>Cette action est irréversible.', () => {
            sendRequest(`${BASE_URL}/admin/user/${userId}/delete`, {})
                .then(data => {
                    if (data.success) {
                        showSuccessToast('Utilisateur supprimé avec succès.');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showErrorToast(data.error || 'Erreur lors de la suppression.');
                    }
                })
                .catch(err => showErrorToast('Erreur de communication.'));
        });
    };

    // --- Actions sur les signalements --- //

    function doUpdateReport(reportId, status, notes) {
        const body = {};
        if (status !== null) body.status = status;
        if (notes !== null) body.notes = notes;

        sendRequest(`${BASE_URL}/admin/report/${reportId}/update-status`, body)
            .then(data => {
                if (data.success) {
                    let message = notes ? 'Note ajoutée.' : 'Statut mis à jour.';
                    showSuccessToast(message);
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showErrorToast(data.error || 'Erreur de mise à jour du signalement.');
                }
            })
            .catch(err => showErrorToast('Erreur de communication.'));
    }
    
    window.updateReportStatus = function(reportId, newStatus) {
        const statusText = { 'pending': 'en attente', 'reviewed': 'examiné', 'resolved': 'résolu' };
        const action = `marquer comme ${statusText[newStatus]}`;
        showConfirmToast(`Voulez-vous vraiment ${action} ce signalement ?`, () => {
            doUpdateReport(reportId, newStatus, null);
        });
    };

    window.addReportNote = function(reportId) {
        document.querySelectorAll('.toastify').forEach(t => t.remove());

        const toastNode = document.createElement('div');
        toastNode.innerHTML = `
            <div style="display:flex;flex-direction:column;align-items:center;width:100%;">
                <h4 style="margin:0 0 10px 0;color:#333;">Note Administrative</h4>
                <textarea id="note-text" class="toast-textarea" placeholder="Entrez votre note..."></textarea>
                <div style="display:flex;justify-content:center;width:100%;margin-top:10px;">
                    <button id="toast-save" class="toast-button yes">Enregistrer</button>
                    <button id="toast-cancel" class="toast-button no">Annuler</button>
                </div>
            </div>
            <style>
                .toast-textarea { width: 100%; min-height: 90px; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; }
                .toast-button { margin: 0 10px; border:none; padding: 8px 18px; border-radius: 6px; cursor: pointer; font-weight: bold; }
                .toast-button.yes { background:#4299e1; color:#fff; }
                .toast-button.no { background:#718096; color:#fff; }
            </style>
        `;

        const toast = Toastify({
            node: toastNode,
            duration: -1,
            close: true,
            gravity: "top",
            position: "center",
            stopOnFocus: true,
            style: { background: "#fff", color: "#222", boxShadow: "0 4px 12px #0003", borderRadius: "10px", width: "350px" }
        });

        const textarea = toastNode.querySelector('#note-text');
        
        toastNode.querySelector('#toast-save').onclick = () => {
            const note = textarea.value.trim();
            if (note) {
                doUpdateReport(reportId, null, note);
                toast.hideToast();
            } else {
                showErrorToast('La note ne peut pas être vide.');
            }
        };
        toastNode.querySelector('#toast-cancel').onclick = () => toast.hideToast();
        
        toast.showToast();
        setTimeout(() => textarea.focus(), 50); // Petit délai pour assurer le focus
    };

    function showEmptyMessage(canvasId, message) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.textAlign = 'center';
        ctx.fillStyle = '#888';
        ctx.font = '16px Arial';
        ctx.fillText(message, canvas.width / 2, canvas.height / 2);
    }

    // Fonction pour charger les statistiques d'abonnement
    function loadSubscriptionStats() {
        fetch(`${BASE_URL}/admin/subscription-stats`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateSubscriptionCharts(data.stats);
                } else {
                    console.error('Erreur lors du chargement des statistiques:', data.error);
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Fonction pour mettre à jour les graphiques d'abonnement
    function updateSubscriptionCharts(stats) {
        // Graphique des statuts
        if (stats.status && stats.status.length > 0) {
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: stats.status.map(s => s.status),
                    datasets: [{
                        data: stats.status.map(s => s.count),
                        backgroundColor: ['#4CAF50', '#F44336', '#FFC107']
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        } else {
            showEmptyMessage('statusChart', 'Aucune donnée de statut');
        }

        // Graphique des revenus mensuels
        if (stats.revenue && stats.revenue.length > 0) {
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: stats.revenue.map(r => r.month),
                    datasets: [{
                        label: 'Revenus (€)',
                        data: stats.revenue.map(r => r.revenue),
                        borderColor: '#2196F3',
                        tension: 0.1
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        } else {
            showEmptyMessage('revenueChart', 'Aucune donnée de revenu');
        }
    }

    // Fonction pour mettre à jour le graphique de répartition des utilisateurs
    function updateUserDistributionChart() {
        const planCanvas = document.getElementById('planChart');
        if (!planCanvas) {
            return;
        }

        const statsData = planCanvas.dataset.stats;
        if (!statsData) {
            showEmptyMessage('planChart', 'Données non trouvées');
            return;
        }

        try {
            const userStats = JSON.parse(statsData);
            if (userStats && (userStats.premium > 0 || userStats.standard > 0)) {
                const planCtx = planCanvas.getContext('2d');
                new Chart(planCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Premium', 'Standard'],
                        datasets: [{
                            data: [userStats.premium, userStats.standard],
                            backgroundColor: ['#8e44ad', '#3498db'],
                            borderColor: '#ffffff',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 20, font: { size: 14 } }
                            },
                            tooltip: {
                                backgroundColor: '#333',
                                titleFont: { size: 14 },
                                bodyFont: { size: 12 },
                                padding: 10,
                                cornerRadius: 6,
                                displayColors: false
                            }
                        }
                    }
                });
            } else {
                showEmptyMessage('planChart', 'Aucune donnée utilisateur');
            }
        } catch (e) {
            console.error("Erreur lors de l'analyse des statistiques JSON:", e);
            showEmptyMessage('planChart', 'Erreur de données');
        }
    }

    // Charger les statistiques au chargement de la page
    if (document.getElementById('statusChart')) {
        loadSubscriptionStats(); // Charge et dessine les graphiques d'abonnement
        updateUserDistributionChart(); // Dessine le graphique de répartition des utilisateurs
    }
}); 