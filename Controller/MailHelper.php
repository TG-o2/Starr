<?php
class MailHelper {
    private $config;
    public function __construct() {
        $this->config = require __DIR__ . '/../config/email_config.php';
    }

    public function send($to, $subject, $body, $headers = []) {
        $from = $this->config['from'] ?? 'no-reply@starr.local';
        $headersStr = 'From: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        if (!empty($headers)) {
            foreach ($headers as $k => $v) {
                $headersStr .= "\r\n" . $k . ': ' . $v;
            }
        }

        $ok = false;
        if (($this->config['driver'] ?? 'mail') === 'mail') {
            $ok = mail($to, $subject, $body, $headersStr);
        } else {
            // Placeholder for SMTP integration (PHPMailer or native SMTP)
            // TODO: Implement SMTP using PHPMailer when credentials are provided
            $ok = mail($to, $subject, $body, $headersStr); // fallback
        }

        // Log
        $logFile = $this->config['log'] ?? (__DIR__ . '/../logs/email.log');
        if (!is_dir(dirname($logFile))) {
            @mkdir(dirname($logFile), 0755, true);
        }
        $line = sprintf("%s | to=%s | subject=%s | ok=%d\n", date('Y-m-d H:i:s'), $to, $subject, $ok ? 1 : 0);
        @file_put_contents($logFile, $line, FILE_APPEND);

        return $ok;
    }
}
