<?php
require_once __DIR__ . '/../admin_guard.php';
/**
 * TESTING BYPASS - Destroys test session
 */

session_unset();
session_destroy();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Session Destroyed</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; text-align: center; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 20px; border-radius: 5px; margin: 20px 0; }
        a { display: inline-block; padding: 10px 20px; margin: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔒 Session Destroyed</h1>
    
    <div class='info'>
        <strong>✓ Done!</strong> Test admin session has been destroyed.<br>
        You will now get 'Access denied' on back office pages.
    </div>
    
    <a href='test_session.php'>🔓 Create Test Session Again</a>
    <a href='loginAdmin.php'>🔑 Go to Admin Login</a>
</body>
</html>";
?>
