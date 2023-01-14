<?php



$pageTitle = 'List of Categories with Items for Each';
include "header.php";

/**
 * Show how to solve n+1 problem and give more flexibility by building a data structure.
 */
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
    -- Using a normal JOIN would not get the categories with 0 items
    LEFT JOIN items i ON c.id = i.category_id
    ORDER BY c.name, i.name;
";
$stmt = $conn->prepare($sql);

$stmt->execute();
$rowCount = $stmt->rowCount();

$lastCategoryId = null;
$lastCategoryName = null;

// Build a 2D array of categories with their items
$categories = [];
// A categoryItems array will become the value for each category
$categoryItems = [];

// Alternative approach: build a data structure with the data we want as a 2D array.
while ($row = $stmt->fetch()) {
    // Render the heading for each category if this category is new
    if (!is_null($lastCategoryId) && $row['category_id'] != $lastCategoryId) {
        $categories[$lastCategoryName] = $categoryItems;
        // Reset the categoryItems array
        $categoryItems = array();
    }

    // Create an array of all the non-null items
    if (!is_null($row['item_id'])) $categoryItems[$row['item_id']] = $row['item_name'];

    $lastCategoryId = $row['category_id'];
    $lastCategoryName = $row['category_name'];
}
// Add the last category to the array with its items
$categories[$lastCategoryName] = $categoryItems;

// Show query stats
$timeEnd = microtime(true);
$timeTotal = round(($timeEnd - $timeStart),2);
echo '<div class="fixed rounded-lg shadow-lg top-2 px-4 py-2 right-8 bg-indigo-800/60 text-white text-center text-lg">';
echo '<h1 class="text-xl font-bold">Query Stats</h1>';
echo $rowCount . ' rows in <b>' . $timeTotal . '</b> seconds</div>';

// Display the table with items and counts
echo '<table class="border-separate m-auto">';

// Now that we've created a data structure from our query,
// use a nested loop to iterate through it, and we can get category counts
foreach ($categories as $categoryName => $items) {
    echo '<tr class="!bg-gray-500/50">';
    echo '  <td colspan="2" class="text-2xl font-bold pt-4 pb-2 pl-4">';
    echo $categoryName;
    // Show the count of items in the category
    echo '<span class="text-sm ml-4">' . count($items) . ' items</span>';
    echo '</td>';
    echo '</tr>';

    // Items heading, or if no items, show "No items"
    if (count($items)) {
        echo '<tr class="!bg-gray-500/50">';
        echo '  <th class="p-2 text-center">ID</th>';
        echo '  <th class="p-2 text-left">Item Name</th>';
        echo '</tr>';

        echo '<tbody class="striped bg-gray-500/5">';
        // Loop through all the items in the category and display them
        foreach($items as $itemId => $itemName) {
            echo '<tr>';
            echo '  <td class="p-2 text-center">' . $itemId.'</td>';
            echo '  <td class="p-2">'.$itemName.'</td>';
            echo '</tr>';
        }

        // Blank row to separate categories
        echo '</tbody>';
        echo '<tr class="!bg-transparent"><td colspan="2" class="py-2"></td></tr>';
    } else {
        echo '<tr class="!bg-transparent"><td colspan="2" class="italic text-sm text-gray-500 text-center pt-2 pb-4">No items</td></tr>';
    }

}
echo '</table>';

include 'footer.php';