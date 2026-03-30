<?php
include 'config.php';

$jsonPath = 'c:/xampp/htdocs/BSU_Kitchen/documents/unzipped/hot_kitchen_table.json';
$data = json_decode(file_get_contents($jsonPath), true);
$cat_id = 1;

echo "<h2>March 2026 Hot Kitchen Inventory Fix (Web)</h2>";

$current_parent = "";
$count = 0;

foreach ($data as $row) {
    if (count($row) < 4) continue;
    $item_name = trim($row[0]);
    $marchQtyRaw = trim($row[3]);
    
    if ($item_name === "" || $item_name === "ITEM") continue;

    if (preg_match('/^\d+[\'"]$/', $item_name) || preg_match('/^\d+oz$/', $item_name) || in_array($item_name, ["Big", "Single", "Double", "2oz", "6oz", "8oz"])) {
        $full_name = $current_parent ? "$current_parent ($item_name)" : $item_name;
    } else {
        $full_name = $item_name;
        if ($marchQtyRaw === "") {
            $current_parent = $item_name;
            continue;
        }
        $current_parent = $item_name;
    }

    preg_match('/^\d+/', $marchQtyRaw, $matches);
    $qty = isset($matches[0]) ? (int)$matches[0] : null;

    if ($qty === null) continue;

    $search_name = mysqli_real_escape_string($con, $full_name);
    $find_q = mysqli_query($con, "SELECT id, item_name FROM lab_items WHERE category_id = $cat_id AND (LOWER(item_name) = LOWER('$search_name') OR item_name LIKE '$search_name%') LIMIT 1");
    $db_item = mysqli_fetch_assoc($find_q);

    if ($db_item) {
        $id = $db_item['id'];
        // Use column names based on JSON provided previously in this task for lab_items query: total_quantity, available_quantity
        mysqli_query($con, "UPDATE lab_items SET total_quantity = $qty, available_quantity = $qty WHERE id = $id");
        echo "✅ Updated: <b>{$db_item['item_name']}</b> to $qty.<br>";
        $count++;
    }
}
echo "<h3>Total updated: $count</h3>";
?>
