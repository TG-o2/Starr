<?php
// Shared config for the Education Corner module. Reuses the main Config PDO.
require_once __DIR__ . '/../config/config.php';

class EducationConfig
{
    public static function getConnection(): PDO
    {
        return Config::getConnexion();
    }
}
