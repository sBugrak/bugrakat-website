<?php
require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

// Start output buffering to prevent unexpected output
ob_start();

try {
    // Load environment variables
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../.');
    $dotenv->load();

    if (!isset($_POST['h-captcha-response'])) {
        ob_end_clean(); // Stop buffering before sending the response
        echo json_encode(['success' => false, 'error' => 'No hCaptcha response provided']);
        exit;
    }

    $data = array(
        'secret' => $_ENV['HCAPTCHA_SECRET'] ?? '',
        'response' => $_POST['h-captcha-response']
    );

    if (empty($data['secret'])) {
        ob_end_clean(); // Stop buffering before sending the response
        echo json_encode(['success' => false, 'error' => 'hCaptcha secret key is missing']);
        exit;
    }

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);

    if (curl_errno($verify)) {
        ob_end_clean(); // Stop buffering before sending the response
        echo json_encode(['success' => false, 'error' => 'Curl error: ' . curl_error($verify)]);
        exit;
    }

    $responseData = json_decode($response);

    if ($responseData && $responseData->success) {
        ob_end_clean(); // Stop buffering before sending the response
        echo json_encode(['success' => true]);
    } else {
        $error = $responseData->error ?? 'Unknown error';
        ob_end_clean(); // Stop buffering before sending the response
        echo json_encode(['success' => false, 'error' => $error]);
    }
} catch (Exception $e) {
    ob_end_clean(); // Stop buffering before sending the response
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
} finally {
    // Clean up any unexpected output
    $output = ob_get_clean();
    if (!empty($output)) {
        error_log('Unexpected output: ' . $output);
    }
}