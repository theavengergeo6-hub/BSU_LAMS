<?php
include 'config.php';

// Load the unzipped JSON table
$jsonPath = 'c:/xampp/htdocs/BSU_Kitchen/documents/unzipped/hot_kitchen_table.json';
if (!file_exists($jsonPath)) {
    die("JSON file not found: $jsonPath");
}

$data = json_decode(file_get_contents($jsonPath), true);
if (!$data) {
    die("Error decoding JSON");
}

// Get Hot Kitchen Category ID
$cat_q = mysqli_query($con, "SELECT id FROM lab_categories WHERE name LIKE '%Hot Kitchen%' LIMIT 1");
$cat_id = mysqli_fetch_assoc($cat_q)['id'];

if (!$cat_id) {
    die("Hot Kitchen category not found in DB");
}

echo "Found Hot Kitchen category ID: $cat_id\n";

$current_parent = "";
$update_count = 0;

foreach ($data as $index => $row) {
    // Skip rows with fewer than 4 cells (we need column index 3 for March 2026)
    if (count($row) < 4) continue;
    
    $item_name = trim($row[0]);
    $marchQty = trim($row[3]);
    
    // Skip empty items or headers
    if ($item_name === "" || $item_name === "ITEM") {
        if ($item_name === "" && trim($row[1]) === "" && $marchQty === "") {
            // Probably a separator row, ignore
        }
        continue;
    }

    // Logic for parent/child names
    // If the name starts with a number and a quote (like 2', 6', 8'), or contains "oz" or other sub-specs,
    // we prefix it with the last known "parent" that had an empty quantity.
    if (preg_match('/^\d+[\'"]$/', $item_name) || preg_match('/^\d+oz$/', $item_name) || $item_name === "Big" || $item_name === "Single" || $item_name === "Double") {
        if ($current_parent) {
            $full_name = "$current_parent ($item_name)";
        } else {
            $full_name = $item_name;
        }
    } else {
        $full_name = $item_name;
        // If the quantity in THIS row is empty, it might be a parent header for the following rows.
        if ($marchQty === "") {
            $current_parent = $item_name;
            continue; // Skip the header row itself
        } else {
            // It has a quantity, so it might not be a header prefixing sub-items (or it's a stand-alone).
            // But if it's not a specifier, reset the parent.
            $current_parent = $item_name; 
        }
    }

    // Sanitize quality/quantity — only take leading numbers
    preg_match('/^\d+/', $marchQty, $matches);
    $qty = isset($matches[0]) ? (int)$matches[0] : 0;

    // Search for item in DB under this category
    $search_name = mysqli_real_escape_string($con, $full_name);
    
    // Try exact match first
    $find_q = mysqli_query($con, "SELECT id, item_name, quantity FROM lab_items WHERE category_id = $cat_id AND (item_name = '$search_name' OR item_name LIKE '$search_name%') LIMIT 1");
    $db_item = mysqli_fetch_assoc($find_q);

    if ($db_item) {
        $id = $db_item['id'];
        $update_q = "UPDATE lab_items SET quantity = $qty, available_quantity = $qty WHERE id = $id";
        if (mysqli_query($con, $update_q)) {
            echo "Updated: {$db_item['item_name']} -> $qty\n";
            $update_count++;
        }
    } else {
        echo "Not found in DB: $full_name (Quantity: $qty)\n";
    }
}

echo "\nTotal items updated: $update_count\n";
?>
