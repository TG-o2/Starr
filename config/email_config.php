<?php
// Email configuration placeholders. Update these for SMTP.
return [
    'driver' => 'mail', // 'smtp' or 'mail'
    'from' => 'no-reply@starr.local',
    'smtp' => [
        'host' => 'smtp.example.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'your_username',
        'password' => 'your_password'
    ],
    'log' => __DIR__ . '/../logs/email.log'
];
