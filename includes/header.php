<?php
declare(strict_types=1);

// automatically load any used classes
include_once "class-autoload.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?= isset($pageTitle) ? "<title>$pageTitle</title>" : '' ?>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="text-gray-800 dark:bg-gray-900 dark:text-gray-100">
<div class="bg-gray-500/50 shadow">
<?php
    echo '<a href="/" class="inline-block text-blue-700 dark:text-blue-300 underline px-4 pt-2">';
    // if this is not index.php, show a home link
    if ($_SERVER['REQUEST_URI'] != '/index.php' && $_SERVER['REQUEST_URI'] != '/') {
        echo '&larr; Table of Contents';
    }
    echo '</a>';
?>
<?= isset($pageTitle) ? '<h1 class="text-3xl p-6 pt-0 font-bold text-center">'.$pageTitle.'</h1>' : '' ?>
</div>
<main class="p-4 mb-12">