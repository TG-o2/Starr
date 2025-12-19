<?php
return [
    'driver' => 'mail',  // or 'smtp' when using PHPMailer
    'from' => $_ENV['GMAIL_USER'] ?? 'no-reply@starr.local',
    'log' => __DIR__ . '/../logs/email.log'
];
?>
