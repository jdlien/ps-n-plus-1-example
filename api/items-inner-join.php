<?php



$pageTitle = 'List of Items with Categories (INNER JOIN)';
include "header.php";

/**
 * Show a simple example of an inner join that shows the category name for each item
 */

?>

<table class="border-separate m-auto">
    <thead class="bg-gray-500/50">
        <tr>
            <th class="p-2 text-center">ID</th>
            <th class="p-2 text-left">Item Name</th>
            <th class="p-2 text-left">CID</th>
            <th class="p-2 text-left">Category</th>
        </tr>
    </thead>
    <tbody class="striped bg-gray-500/5">
    <?php
    $dbh = new Dbh();
    $conn = $dbh->connect();
    // Record the time before the first query is executed
    $timeStart = microtime(true);

    $sql = "
        SELECT
            i.id AS item_id,
            i.name as item_name,
            c.id AS category_id,
            c.name AS category_name
        FROM items i
        -- JOIN is equivalent to INNER JOIN
        JOIN categories c ON i.category_id = c.id
        ORDER BY i.id;
    ";
    $stmt = $conn->prepare($sql);

    $stmt->execute();

    while ($row = $stmt->fetch()) {
        echo '<tr>';
        echo '  <td class="px-4">'.$row['item_id'].'</td>';
        echo '  <td class="px-4">'.$row['item_name'].'</td>';
        echo '  <td class="px-4 text-center">'.$row['category_id'].'</td>';
        echo '  <td class="px-4">'.$row['category_name'].'</td>';
        echo '</tr>';
    }

    // Show query stats
    $timeEnd = microtime(true);
    $timeTotal = round(($timeEnd - $timeStart),2);
    echo '<div class="fixed rounded-lg shadow-lg top-2 px-4 py-2 right-8 bg-indigo-800/60 text-white text-center text-lg">';
    echo '<h1 class="text-xl font-bold">Query Stats</h1>';
    echo $stmt->rowCount() . ' rows in <b>' . $timeTotal . '</b> seconds</div>';

    ?>
    </tbody>
</table>
<?php

include 'footer.php';