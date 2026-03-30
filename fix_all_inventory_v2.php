<?php
include 'config.php';

$inventory_configs = [
    [
        'name' => 'Hot Kitchen',
        'file' => 'hot_kitchen_table.json',
        'target_column' => 3, // March 2026
        'search_pattern' => 'Hot Kitchen'
    ],
    [
        'name' => 'FB Services',
        'file' => 'fb_services_table.json',
        'target_column' => 3, // March 2026
        'search_pattern' => 'Food & Beverage'
    ],
    [
        'name' => 'Laundry / Linens',
        'file' => 'laundry_table.json',
        'target_column' => 9, // December 2025
        'search_pattern' => 'Laundry'
    ]
];

echo "<h1>LAMS Global Inventory Sync V2 - Fixed Parents</h1>";

foreach ($inventory_configs as $config) {
    echo "<h3>Syncing {$config['name']}...</h3>";
    $jsonPath = "c:/xampp/htdocs/BSU_Kitchen/documents/unzipped/{$config['file']}";
    $data = json_decode(file_get_contents($jsonPath), true);
    
    // Get Category ID
    $cat_q = mysqli_query($con, "SELECT id FROM lab_categories WHERE name LIKE '%{$config['search_pattern']}%' LIMIT 1");
    $cat_res = mysqli_fetch_assoc($cat_q);
    $cat_id = $cat_res['id'];

    $current_parent = "";
    $count = 0;
    
    foreach ($data as $row) {
        $item_name = trim($row[0]);
        $qtyRaw = trim($row[$config['target_column']]);
        if ($item_name === "" || $item_name === "ITEM") continue;

        // Correct Header logic: if quantity is empty, it's a parent.
        if ($qtyRaw === "") {
            $current_parent = $item_name;
            continue;
        }

        // Sub-item logic
        if (preg_match('/^\d+[\'"]$/', $item_name) || in_array($item_name, ["Big", "Single", "Double", "2oz", "6oz", "8oz", "Set", "Small", "White"])) {
            $full_name = $current_parent ? "$current_parent ($item_name)" : $item_name;
        } else {
            $full_name = $item_name;
            // This is NOT a header (has qty), so we don't necessarily update parent?
            // Usually headers are standalone rows.
        }

        preg_match('/^\d+/', $qtyRaw, $matches);
        $qty = ($matches && isset($matches[0])) ? (int)$matches[0] : null;

        if ($qty === null) continue;

        $search_name = mysqli_real_escape_string($con, $full_name);
        $find_q = mysqli_query($con, "SELECT id FROM lab_items WHERE (LOWER(item_name) = LOWER('$search_name') OR item_name LIKE '$search_name%') AND category_id = $cat_id LIMIT 1");
        $db_item = mysqli_fetch_assoc($find_q);

        if ($db_item) {
            $id = $db_item['id'];
            mysqli_query($con, "UPDATE lab_items SET item_name = '$search_name', total_quantity = $qty, available_quantity = $qty WHERE id = $id");
            echo "✅ $search_name -> $qty<br>";
            $count++;
        }
    }
    echo "<p>Total updated: $count</p><hr>";
}
?>
