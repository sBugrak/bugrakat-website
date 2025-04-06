<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' https://www.hcaptcha.com https://js.hcaptcha.com; frame-src https://newassets.hcaptcha.com;">
    <link rel="icon" href="assets/favicon.ico">
    <title>BUGKAT</title>
    <!-- Base CSS -->
    <link rel="stylesheet" href="style/reset.css?v=<?php echo filemtime('style/reset.css'); ?>">
    <link rel="stylesheet" href="style/main.css?v=<?php echo filemtime('style/main.css'); ?>">
    <link rel="stylesheet" href="style/navbar.min.css?v=<?php echo filemtime('style/navbar.min.css'); ?>">
    <link rel="stylesheet" href="style/footer.css?v=<?php echo filemtime('style/footer.css'); ?>">
    <?php
    $page = $_GET['page'] ?? 'start';
    $pageCSS = [
        'start' => 'style/index/startup.css',
        'about' => 'style/index/about.css',
        'register' => 'style/index/register.css',
        'enter' => 'style/index/enter.css'
    ];
    if (isset($pageCSS[$page])) {
        $cssFile = $pageCSS[$page];
        echo "<link rel='stylesheet' href='$cssFile?v=" . filemtime($cssFile) . "'>\n";
    }
    ?>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <main>
        <?php
        $componentFile = "components/index/$page.php";
        if (file_exists($componentFile)) {
            include $componentFile;
        } else {
            include 'components/index/startup.php';
        }
        ?>
    </main>
    <?php include 'components/footer.php'; ?>
</body>
</html>