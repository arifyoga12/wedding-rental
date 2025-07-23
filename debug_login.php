<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Database;
use App\Models\User;

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    $_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
    $_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'wedding_rental';
    $_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
    $_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';
}

echo "<h1>🔍 Debug Login Process</h1>";

// Initialize database
try {
    Database::init();
    echo "<p>✅ Database initialized</p>";
} catch (Exception $e) {
    echo "<p>❌ Database init failed: " . $e->getMessage() . "</p>";
    exit;
}

// Test credentials
$testEmail = 'admin@wedding.com';
$testPassword = 'admin123';

echo "<h2>🧪 Testing Login Process</h2>";
echo "<p><strong>Email:</strong> $testEmail</p>";
echo "<p><strong>Password:</strong> $testPassword</p>";

// Step 1: Find user by email
echo "<h3>Step 1: Find User by Email</h3>";
try {
    $user = User::findByEmail($testEmail);
    if ($user) {
        echo "<p>✅ User found</p>";
        echo "<p><strong>ID:</strong> {$user->id}</p>";
        echo "<p><strong>Name:</strong> {$user->name}</p>";
        echo "<p><strong>Email:</strong> {$user->email}</p>";
        echo "<p><strong>Password Hash:</strong> " . substr($user->password, 0, 20) . "...</p>";
    } else {
        echo "<p>❌ User not found</p>";
        exit;
    }
} catch (Exception $e) {
    echo "<p>❌ Error finding user: " . $e->getMessage() . "</p>";
    exit;
}

// Step 2: Test password verification
echo "<h3>Step 2: Password Verification</h3>";
$isValid = password_verify($testPassword, $user->password);
echo "<p><strong>Password Verify Result:</strong> " . ($isValid ? "✅ VALID" : "❌ INVALID") . "</p>";

if (!$isValid) {
    echo "<p>🔧 Let's fix the password...</p>";
    
    // Fix password
    $db = Database::getConnection();
    $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
    $result = $stmt->execute([$newHash, $testEmail]);
    
    if ($result) {
        echo "<p>✅ Password updated successfully</p>";
        
        // Test again
        $testVerify = password_verify($testPassword, $newHash);
        echo "<p><strong>New Password Test:</strong> " . ($testVerify ? "✅ VALID" : "❌ STILL INVALID") . "</p>";
    } else {
        echo "<p>❌ Failed to update password</p>";
    }
}

// Step 3: Test authenticate method
echo "<h3>Step 3: Test Authenticate Method</h3>";
try {
    $authUser = User::authenticate($testEmail, $testPassword);
    if ($authUser) {
        echo "<p>✅ Authentication successful</p>";
        echo "<p><strong>Authenticated User:</strong> {$authUser->name} ({$authUser->email})</p>";
    } else {
        echo "<p>❌ Authentication failed</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Authentication error: " . $e->getMessage() . "</p>";
}

// Step 4: Test direct database query
echo "<h3>Step 4: Direct Database Test</h3>";
try {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$testEmail]);
    $dbUser = $stmt->fetch();
    
    if ($dbUser) {
        echo "<p>✅ Direct DB query successful</p>";
        echo "<p><strong>DB User ID:</strong> {$dbUser['id']}</p>";
        echo "<p><strong>DB User Name:</strong> {$dbUser['name']}</p>";
        echo "<p><strong>DB User Email:</strong> {$dbUser['email']}</p>";
        
        $directVerify = password_verify($testPassword, $dbUser['password']);
        echo "<p><strong>Direct Password Verify:</strong> " . ($directVerify ? "✅ VALID" : "❌ INVALID") . "</p>";
    } else {
        echo "<p>❌ Direct DB query failed</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Direct DB error: " . $e->getMessage() . "</p>";
}

// Step 5: Test login API endpoint simulation
echo "<h3>Step 5: Simulate Login API Call</h3>";
session_start();

// Simulate POST data
$_POST = [
    'email' => $testEmail,
    'password' => $testPassword
];

try {
    $user = User::authenticate($testEmail, $testPassword);
    if ($user) {
        $_SESSION['user'] = $user->toArray();
        echo "<p>✅ Login simulation successful</p>";
        echo "<p><strong>Session User:</strong> " . json_encode($_SESSION['user']) . "</p>";
        
        // Check admin status
        if ($user->email === 'admin@wedding.com') {
            echo "<p>✅ Admin user detected - should redirect to /admin</p>";
        } else {
            echo "<p>ℹ️ Regular user - should redirect to /</p>";
        }
    } else {
        echo "<p>❌ Login simulation failed</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Login simulation error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🚀 Next Steps</h2>";
echo "<p>1. If all tests pass, try logging in again at: <a href='/wedding-rental/auth'>Login Page</a></p>";
echo "<p>2. If tests fail, check the error messages above</p>";
echo "<p>3. Delete this debug file after testing: <code>debug_login.php</code></p>";
?>