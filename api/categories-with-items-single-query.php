<?php



$pageTitle = 'List of Categories with Items for Each';
include "header.php";

/**
 * Show how to solve n+1 problem by using a LEFT JOIN.
 */
?>

<table class="border-separate m-auto">
    <?php
    $dbh = new Dbh();
    $conn = $dbh->connect();
    // Record the time before the query is executed
    $timeStart = microtime(true);

    $sql = "
        SELECT
            c.id AS category_id,
            c.name AS category_name,
            i.id AS item_id,
            i.name AS item_name
        FROM categories c
        LEFT JOIN items i ON c.id = i.category_id
        ORDER BY c.name, i.name;
    ";
    $stmt = $conn->prepare($sql);

    $stmt->execute();
    $rowCount = $stmt->rowCount();

    $lastCategoryId = null;

    while ($row = $stmt->fetch()) {
        // Render the heading for each category if this category is new
        if ($row['category_id'] != $lastCategoryId) {
            // Blank row to separate categories
            if (!is_null($lastCategoryId)) {
                echo '</tbody>';
                echo '<tr colspan="2" class="!bg-transparent"><td class="py-2"></td></tr>';
            }
            echo '<tbody class="striped bg-gray-500/5">';
            echo '<tr class="!bg-gray-500/50">';
            echo   '<td colspan="2" class="text-2xl font-bold pt-4 pb-2 pl-4">';
            echo     $row['category_name'];
            echo   '</td>';
            echo '</tr>';
            echo '<tr class="!bg-gray-500/50">';
            echo   '<th class="p-2 text-center">ID</th>';
            echo   '<th class="p-2 text-left">Item Name</th>';
            echo '</tr>';
        }

        // Display the row for each item
        if (!is_null($row['item_id'])) {
            echo '<tr>';
            echo '  <td class="p-2 text-center">' . $row['item_id'].'</td>';
            echo '  <td class="p-2">'.$row['item_name'].'</td>';
            echo '</tr>';
        }


        $lastCategoryId = $row['category_id'];
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

include 'footer.php';