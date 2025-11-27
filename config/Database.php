<?php

namespace Config;

use PDO;
use PDOException;

/**
 * Gestionnaire de connexion à la base de données
 */
class Database
{
    private static ?PDO $connection = null;

    /**
     * Obtenir la connexion PDO (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $host    = $_ENV['MYSQL_HOST']     ?? 'mysql';
                $dbname  = $_ENV['MYSQL_DATABASE'] ?? 'myweeklyallowance';
                $user    = $_ENV['MYSQL_USER']     ?? 'user';
                $pass    = $_ENV['MYSQL_PASSWORD'] ?? 'password';
                
                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        // die("ok");
        return self::$connection;
    }
}
