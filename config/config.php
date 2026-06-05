<?php
class Config
{
    private static $pdo = null;

    private static function loadEnvFile(): void
    {
        $envPath = __DIR__ . '/../.env';
        if (!file_exists($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }

    private static function env(string $key, string $default = ''): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === false || $value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            self::loadEnvFile();

            $servername = self::env('DB_HOST', 'localhost');
            $username = self::env('DB_USER', 'root');
            $password = self::env('DB_PASS', '');
            $dbname = self::env('DB_NAME', 'Starr');

            try {
                self::$pdo = new PDO(
                    "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (Exception $e) {
                die('Error: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
?>