<?php
$pageTitle = 'List of Categories with Items for Each';
include "includes/header.php";

/**
 * An example of an n+1 query problem.
 * Takes far longer than doing it with a single query.
 */

?>

<table class="border-separate m-auto">
    <tbody class="striped bg-gray-500/5">
    <?php
    $dbh = new Dbh();
    $conn = $dbh->connect();
    // Record the time before the query is executed
    $timeStart = microtime(true);

    $sql = "
        SELECT id, name FROM categories
        ORDER BY name;
    ";
    $stmt = $conn->prepare($sql);

    $stmt->execute();
    $rowCount = $stmt->rowCount();

    while ($row = $stmt->fetch()) {

        echo '<tr class="!bg-gray-500/50">';
        echo '  <td colspan="2" class="text-2xl font-bold pt-4 pl-4 pb-2">'.$row['name'].'</td>';
        echo '</tr>';
        echo '<tr class="!bg-gray-500/50">';
            echo '<th class="p-2 text-center">ID</th>';
            echo '<th class="p-2 text-left">Item Name</th>';
        echo '</tr>';

        // Now query for the items for this category
        $sql = "
            SELECT id, name FROM items
            WHERE category_id = :category_id
            ORDER BY name;
        ";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bindParam(':category_id', $row['id']);
        $stmt2->execute();
        $rowCount += $stmt2->rowCount();

        while ($row2 = $stmt2->fetch()) {
            echo '<tr>';
            echo '  <td class="p-2 text-center">'.$row2['id'].'</td>';
            echo '  <td class="p-2">'.$row2['name'].'</td>';
            echo '</tr>';
        }
        // Blank row to separate categories
        echo '<tr colspan="2" class="!bg-transparent"><td class="py-2"></td></tr>';
    }

    // Show query stats
    $timeEnd = microtime(true);
    $timeTotal = round(($timeEnd - $timeStart),2);
    echo '<div class="fixed rounded-lg shadow-lg top-2 px-4 py-2 right-8 bg-indigo-800/60 text-white text-center text-lg">';
    echo '<h1 class="text-xl font-bold">Query Stats</h1>';
    echo $rowCount . ' rows in <b>' . $timeTotal . '</b> seconds</div>';

    ?>
    </tbody>
</table>
<?php

include 'includes/footer.php';