<?php

namespace App\Core;
 
class Model
{
    protected $db;

    public function __construct() {
        $this->db = new \PDO('mysql:host=localhost;dbname=evergem', 'root', 'root');
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
} 