<?php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;


require '../vendor/autoload.php';

$pageTitle = 'Table of Contents';
include '../includes/header.php';

$nPlus1Pages = array(
    [
        'url' => 'categories-with-items.php',
        'title' => 'Categories with Items (n+1)',
    ],
    [
        'url' => 'categories-with-items-single-query.php',
        'title' => 'Categories with Items Single Query',
    ],
    [
        'url' => 'categories-with-items-counts.php',
        'title' => 'Categories with Items and Counts (n+1)',
    ],
    [
        'url' => 'categories-with-items-counts-single-query.php',
        'title' => 'Categories with Items and Counts Single Query (data structure)',
    ],
);
?>

<div class="pl-8">
    <?php


    function displayExampleList(string $title, array $pageList): void
    {
        echo '<div class="mt-16">';
        echo '<h1 class="text-4xl font-bold mb-3">'.$title.'</h1>';
        echo '</div>';

        $count = 0;
        foreach ($pageList as $page) {
            echo '<div class="p-0.5">';
            $count++;
            if (array_key_exists('url', $page)) {
                echo '  <span class="w-4 inline-block text-right mr-2">' . $count . '.</span>';
                echo '  <a href="' . $page['url'] . '" class="text-blue-600 dark:text-blue-400 hover:underline">';
                echo $page['title'];
                echo '  </a>';
            } else {
                // Show title as a heading if no url exists
                echo '<h2 class="font-bold text-2xl mt-6 mb-1">'.$page['title'].'</h2>';
            }
            echo '</div>';
        }
        echo '</div>';
    }

    displayExampleList('n+1 Query Examples', $nPlus1Pages);

    ?>
</div>

<?php

include '../includes/footer.php';
