<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Conversation;

class MessageController extends Controller
{
    public function index()
    {
        // Pour l’instant, on simule l’id de l’utilisateur connecté (à remplacer par la session ou l’authentification)
        $userId = 1;
        $conversations = Conversation::getConversations($userId);
        $this->render('messages/index', ['conversations' => $conversations]);
    }
} 