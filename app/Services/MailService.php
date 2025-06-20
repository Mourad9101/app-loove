<?php

namespace app\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    private PHPMailer $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['PHP_MAILER_USERNAME'] ?? '';
        $this->mailer->Password = $_ENV['PHP_MAILER_PASSWORD'] ?? '';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->setFrom($_ENV['PHP_MAILER_USERNAME'] ?? '', "EverGem");
    }

    public function send_to(string $email, string $name = ''): void {
        $this->mailer->addAddress($email, $name);
    }

    public function set_subject(string $subject): void {
        $this->mailer->Subject = $subject;
    }

    public function set_HTML_body_with_code(string $templateFilename, array $data): void {
        $templatePath = realpath($templateFilename);

        if (!$templatePath || !file_exists($templatePath)) {
            throw new \Exception("Template introuvable : " . $templatePath);
        }

        $email_body = file_get_contents($templatePath);

        foreach ($data as $key => $value) {
            $email_body = str_replace('{{' . $key . '}}', htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $email_body);
        }

        $this->mailer->isHTML(true);
        $this->mailer->Body = $email_body;
    }

    public function send_mail(): void {
        try {
            $this->mailer->send();
        } catch (Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage();
        }
    }
} 