<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public string $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $password;
    public string $created_at;

    public static function create(string $name, string $email, string $password, ?string $phone = null): ?self
    {
        try {
            error_log("User::create called with name: $name, email: $email");
            
            $db = Database::getConnection();
            error_log("Database connection obtained");
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            error_log("Password hashed successfully");
            
            $stmt = $db->prepare("
                INSERT INTO users (name, email, password, phone, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            error_log("SQL statement prepared");
            
            $result = $stmt->execute([$name, $email, $hashedPassword, $phone]);
            error_log("SQL execute result: " . ($result ? 'true' : 'false'));
            
            if ($result) {
                $insertId = $db->lastInsertId();
                error_log("User inserted with ID: $insertId");
                return self::findByEmail($email);
            } else {
                error_log("SQL execute failed");
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error: " . json_encode($errorInfo));
                return null;
            }
        } catch (PDOException $e) {
            error_log("PDO Exception in User::create: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            throw $e;
        } catch (Exception $e) {
            error_log("General Exception in User::create: " . $e->getMessage());
            throw $e;
        }
    }

    public static function findByEmail(string $email): ?self
    {
        try {
            error_log("User::findByEmail called with: " . $email);
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            $userData = $stmt->fetch();
            if ($userData) {
                error_log("User found in database: " . $userData['name'] . " (ID: " . $userData['id'] . ")");
                $user = new self();
                $user->id = $userData['id'];
                $user->name = $userData['name'];
                $user->email = $userData['email'];
                $user->phone = $userData['phone'];
                $user->password = $userData['password'];
                $user->created_at = $userData['created_at'];
                return $user;
            }
            
            error_log("User not found in database for email: " . $email);
            return null;
        } catch (PDOException $e) {
            error_log("Find user error: " . $e->getMessage());
            return null;
        }
    }

    public static function authenticate(string $email, string $password): ?self
    {
        error_log("User::authenticate called with email: " . $email);
        $user = self::findByEmail($email);
        
        if (!$user) {
            error_log("Authentication failed: User not found");
            return null;
        }
        
        error_log("Testing password for user: " . $user->name);
        $passwordValid = password_verify($password, $user->password);
        error_log("Password verification result: " . ($passwordValid ? "VALID" : "INVALID"));
        error_log("Stored hash: " . substr($user->password, 0, 20) . "...");
        
        if ($passwordValid) {
            error_log("Authentication successful for: " . $user->email);
            return $user;
        }
        
        error_log("Authentication failed: Invalid password for: " . $user->email);
        return null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}