document.addEventListener('DOMContentLoaded', function() {
    const stack = document.getElementById('profiles-stack');
    let isDragging = false;
    let startX = 0;
    let currentX = 0;

    function handleStart(e) {
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        isDragging = true;
        startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
        card.style.transition = 'none';
    }

    function handleMove(e) {
        if (!isDragging) return;
        
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        e.preventDefault();
        const clientX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
        currentX = clientX - startX;
        
        const rotate = currentX * 0.05;
        card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

    }

    function handleEnd() {
        if (!isDragging) return;
        
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        isDragging = false;
        const threshold = window.innerWidth * 0.25;
        
        if (Math.abs(currentX) > threshold) {
            const isLike = currentX > 0;
            handleSwipe(card, isLike ? 'like' : 'pass');
        } else {
            card.style.transform = '';
            card.style.transition = 'transform 0.3s ease';
        }
        
        currentX = 0;
    }

    function handleSwipe(card, actionType) {
        const userId = card.dataset.userId;
        let endpoint = '';

        switch (actionType) {
            case 'pass':
                endpoint = 'pass';
                break;
            case 'like':
                endpoint = 'like';
                break;
            case 'gem':
                endpoint = 'gem';
                break;
            default:
                return;
        }
        
        card.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
        
        let transformX = '0';
        if (actionType === 'like' || actionType === 'gem') {
            transformX = '1000px';
        } else if (actionType === 'pass') {
            transformX = '-1000px';
        }
        
        card.style.transform = `translateX(${transformX}) rotate(${currentX * 0.05}deg)`;

        setTimeout(() => {
            card.remove();
            document.querySelectorAll('.profile-card').forEach((remainingCard, idx) => {
                remainingCard.dataset.index = idx;
                remainingCard.style.zIndex = stack.children.length - idx;
            });
            loadMoreProfiles();
        }, 500);

        fetch(`${BASE_URL}/matches/${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.matches_remaining !== undefined && data.matches_remaining >= 0) {
                    const countElement = document.getElementById('matches-remaining-count');
                    if (countElement) {
                        countElement.textContent = data.matches_remaining;
                        
                        if (data.matches_remaining === 0) {
                            const limitInfo = document.querySelector('.match-limit-info');
                            if(limitInfo) {
                                limitInfo.innerHTML = '<p>Vous avez atteint votre limite de matchs quotidiens.</p><p>Passez <a href="/payment" class="premium-link">Premium</a> pour des matchs illimités !</p>';
                            }
                        }
                    }
                }

                if (data.match) {
                    alert('C\'est un Match !');
                }
            } else {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Une erreur est survenue lors de l\'action.');
                }
            }
        })
        .catch(error => {
            console.error('Erreur de communication:', error);
            alert('Erreur de communication avec le serveur.');
        });
    }

    document.querySelectorAll('.btn-pass').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            handleSwipe(card, 'pass');
        });
    });

    document.querySelectorAll('.btn-like').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            handleSwipe(card, 'like');
        });
    });

    document.querySelectorAll('.btn-gem').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            handleSwipe(card, 'gem');
        });
    });

    const bioElements = document.querySelectorAll('.profile-bio');
    bioElements.forEach(bio => {
        if (bio.scrollHeight > bio.clientHeight) {
            bio.classList.add('scrollable');
        }
    });

    let isLoadingMore = false;
    function loadMoreProfiles() {
        if (isLoadingMore) return;

        const currentProfilesCount = document.querySelectorAll('.profile-card').length;
        const minAge = document.getElementById('min_age') ? document.getElementById('min_age').value : '';
        const maxAge = document.getElementById('max_age') ? document.getElementById('max_age').value : '';
        const gender = document.getElementById('gender') ? document.getElementById('gender').value : '';
        const gemstone = document.getElementById('gemstone') ? document.getElementById('gemstone').value : '';
        const radius = document.getElementById('radius') ? document.getElementById('radius').value : '';

        let queryString = `offset=${currentProfilesCount}&limit=10`;
        if (minAge) queryString += `&min_age=${minAge}`;
        if (maxAge) queryString += `&max_age=${maxAge}`;
        if (gender) queryString += `&gender=${gender}`;
        if (gemstone) queryString += `&gemstone=${gemstone}`;
        if (radius) queryString += `&radius=${radius}`;

        if (currentProfilesCount < 3) {
            isLoadingMore = true;
            fetch(`${BASE_URL}/matches/load-more?${queryString}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.users && data.users.length > 0) {
                    data.users.forEach(newProfile => {
                        appendProfileCard(newProfile);
                    });
                } else {
                    console.log('Plus de profils à charger.');
                }
            })
            .catch(error => {
                console.error('Erreur de chargement des profils:', error);
            })
            .finally(() => {
                isLoadingMore = false;
            });
        }
    }

    function appendProfileCard(user) {
        const newIndex = stack.children.length;
        const newCard = document.createElement('div');
        newCard.classList.add('profile-card');
        newCard.dataset.userId = user.id;
        newCard.dataset.index = newIndex;
        newCard.style.zIndex = stack.children.length - newIndex;

        const imageUrl = `${BASE_URL}/public/uploads/${user.image || 'default.jpg'}`;
        const gemColor = getGemColor(user.gemstone || 'Diamond');

        newCard.innerHTML = `
            <div class="profile-image" style="background-image: url('${imageUrl}')">
                <div class="profile-gradient"></div>
                <div class="profile-info-bottom">
                    <div class="profile-name">${user.first_name || ''}${user.age !== undefined ? ', ' + user.age : ''}</div>
                    <div class="profile-location">${(user.city || '').toUpperCase()}${(user.country !== undefined && user.country !== null) ? ', ' + user.country.toUpperCase() : ''}</div>
                </div>
            </div>
            <div class="profile-content">
                <div class="profile-bottom-drag"></div>
                <div class="profile-about-title">About me</div>
                <div class="profile-bio">${user.bio || ''}</div>
                ${user.interests && user.interests.length > 0 ? `
                    <div class="profile-interests-title">Interest</div>
                    <div class="profile-interests">
                        ${user.interests.map(interest => `<span class="interest-tag">${interest}</span>`).join('')}
                    </div>
                ` : ''}
                <div class="profile-gemstone-bottom">
                    <i class="fas fa-gem" style="color:${gemColor}"></i>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn-action btn-pass"><i class="fas fa-times"></i></button>
                <button class="btn-action btn-gem"><i class="fas fa-gem"></i></button>
                <button class="btn-action btn-like"><i class="fas fa-heart"></i></button>
                <button class="btn-action btn-report"><i class="fas fa-flag"></i></button>
            </div>
        `;

        stack.appendChild(newCard);
        addCardEventListeners(newCard);
    }

    function addCardEventListeners(cardElement) {
        cardElement.querySelector('.btn-pass').addEventListener('click', function() {
            handleSwipe(cardElement, 'pass');
        });
        cardElement.querySelector('.btn-like').addEventListener('click', function() {
            handleSwipe(cardElement, 'like');
        });
        cardElement.querySelector('.btn-gem').addEventListener('click', function() {
            handleSwipe(cardElement, 'gem');
        });
        cardElement.querySelector('.btn-report').addEventListener('click', function() {
            const userId = cardElement.dataset.userId;
            reportUser(userId);
        });

        const bio = cardElement.querySelector('.profile-bio');
        if (bio && bio.scrollHeight > bio.clientHeight) {
            bio.classList.add('scrollable');
        }
    }

    document.querySelectorAll('.profile-card').forEach(card => {
        addCardEventListeners(card);
    });

    stack.addEventListener('mousedown', handleStart);
    stack.addEventListener('touchstart', handleStart);
    stack.addEventListener('mousemove', handleMove);
    stack.addEventListener('touchmove', handleMove);
    stack.addEventListener('mouseup', handleEnd);
    stack.addEventListener('touchend', handleEnd);
    stack.addEventListener('mouseleave', handleEnd);

    loadMoreProfiles();

    function reportUser(reportedUserId) {
        const reason = prompt('Quelle est la raison du signalement ?');
        if (reason !== null && reason.trim() !== '') {
            fetch(`${BASE_URL}/report/${reportedUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Utilisateur signalé avec succès. Merci de votre aide !');
                } else {
                    alert(data.error || 'Une erreur est survenue lors du signalement.');
                }
            })
            .catch(error => {
                alert('Erreur de communication avec le serveur.');
            });
        }
    }
}); 