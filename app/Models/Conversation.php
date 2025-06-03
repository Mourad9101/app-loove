<?php

namespace App\Models;

use App\Core\Model;

class Conversation extends Model
{
    public static function getConversations(int $userId) : array
    {
        // Pour l'instant, on simule des conversations fictives (à remplacer par une requête en base de données)
        $conversations = [
            [
                'id' => 1,
                'recipient_name' => 'Alice',
                'last_message' => 'Bonjour, comment ça va ?'
            ],
            [
                'id' => 2,
                'recipient_name' => 'Bob',
                'last_message' => 'À bientôt !'
            ],
            [
                'id' => 3,
                'recipient_name' => 'Charlie',
                'last_message' => 'Merci pour ton message.'
            ]
        ];
        return $conversations;
    }
} 