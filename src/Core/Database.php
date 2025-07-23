<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function init(): void
    {
        try {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $dbname = $_ENV['DB_NAME'] ?? 'wedding_rental';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';

            error_log("Database init - Host: $host, DB: $dbname, User: $username");

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            
            self::$connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            error_log("PDO connection created successfully");
            
            // Test connection
            $testResult = self::$connection->query('SELECT 1');
            error_log("Database connection test successful");
            
            // Test if users table exists
            $tableCheck = self::$connection->query("SHOW TABLES LIKE 'users'");
            $tableExists = $tableCheck->rowCount() > 0;
            error_log("Users table exists: " . ($tableExists ? 'yes' : 'no'));
            
            if (!$tableExists) {
                error_log("ERROR: Users table does not exist! Please import the database schema.");
            }
            
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            error_log("PDO Error Code: " . $e->getCode());
            error_log("PDO SQL State: " . $e->errorInfo[0] ?? 'unknown');
            die("Koneksi database gagal. Pastikan MySQL berjalan dan database 'wedding_rental' sudah dibuat.");
        }
    }

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::init();
        }
        return self::$connection;
    }
}