<?php
$PROJECT_ROOT = dirname(dirname(__FILE__));
require $PROJECT_ROOT . '/vendor/autoload.php';
require $PROJECT_ROOT . '/config/google-config.php';
require $PROJECT_ROOT . '/includes/session.php';

try {
    $client = new Google\Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->setApprovalPrompt('force');

    $client->addScope('email');
    $client->addScope('profile');

    $state = generateOAuthState();
    $client->setState($state);
    
    $authUrl = $client->createAuthUrl();

    header('Location: ' . $authUrl);
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = htmlspecialchars($e->getMessage());
    header('Location: ../public/login.php');
    exit;
}
?>
