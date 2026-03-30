const fs = require('fs');

const hotKitchen = require('./documents/unzipped/hot_kitchen_table.json');
const fbServices = require('./documents/unzipped/fb_services_table.json');
const laundry = require('./documents/unzipped/laundry_table.json');

let sql = `
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2026

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS \`bsu_lab_assets\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE \`bsu_lab_assets\`;

-- --------------------------------------------------------

CREATE TABLE \`lab_admin_notifications\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`title\` varchar(255) NOT NULL,
  \`message\` text NOT NULL,
  \`is_read\` tinyint(1) DEFAULT 0,
  \`created_at\` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (\`id\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE \`lab_admin_users\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`username\` varchar(50) NOT NULL,
  \`email\` varchar(100) NOT NULL,
  \`password\` varchar(255) NOT NULL,
  \`role\` varchar(20) DEFAULT 'admin',
  \`created_at\` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (\`id\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO \`lab_admin_users\` (\`id\`, \`username\`, \`email\`, \`password\`, \`role\`) VALUES
(1, 'admin', 'admin@bsu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- password is 'password'

CREATE TABLE \`lab_categories\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`name\` varchar(100) NOT NULL,
  PRIMARY KEY (\`id\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO \`lab_categories\` (\`id\`, \`name\`) VALUES
(1, 'Hot Kitchen Tools'),
(2, 'Cold Kitchen Tools'),
(3, 'Food & Beverage Service'),
(4, 'Linens'),
(5, 'Laundry Tools & Linens');

CREATE TABLE \`lab_items\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`category_id\` int(11) NOT NULL,
  \`item_name\` varchar(255) NOT NULL,
  \`unit\` varchar(50) DEFAULT 'piece',
  \`total_quantity\` int(11) NOT NULL DEFAULT 0,
  \`available_quantity\` int(11) NOT NULL DEFAULT 0,
  \`image_path\` varchar(255) DEFAULT NULL,
  \`min_threshold\` int(11) DEFAULT 5,
  \`created_at\` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (\`id\`),
  KEY \`category_id\` (\`category_id\`),
  CONSTRAINT \`lab_items_ibfk_1\` FOREIGN KEY (\`category_id\`) REFERENCES \`lab_categories\` (\`id\`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE \`lab_item_logs\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`item_id\` int(11) NOT NULL,
  \`change_type\` varchar(20) NOT NULL,
  \`quantity\` int(11) NOT NULL,
  \`remarks\` varchar(255) DEFAULT NULL,
  \`performed_by\` int(11) DEFAULT NULL,
  \`created_at\` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (\`id\`),
  KEY \`item_id\` (\`item_id\`),
  CONSTRAINT \`lab_item_logs_ibfk_1\` FOREIGN KEY (\`item_id\`) REFERENCES \`lab_items\` (\`id\`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE \`lab_reservations\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`reservation_no\` varchar(50) NOT NULL,
  \`student_name\` varchar(100) NOT NULL,
  \`student_email\` varchar(100) NOT NULL,
  \`contact_number\` varchar(20) NOT NULL,
  \`subject\` varchar(100) NOT NULL,
  \`course_section\` varchar(50) NOT NULL,
  \`station\` varchar(50) NOT NULL,
  \`batch\` varchar(50) NOT NULL,
  \`reservation_date\` date NOT NULL,
  \`reservation_time\` varchar(50) NOT NULL,
  \`status\` enum('Pending','Approved','Ongoing','Completed','Denied') DEFAULT 'Pending',
  \`created_at\` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (\`id\`),
  UNIQUE KEY \`reservation_no\` (\`reservation_no\`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE \`lab_reservation_items\` (
  \`id\` int(11) NOT NULL AUTO_INCREMENT,
  \`reservation_id\` int(11) NOT NULL,
  \`item_id\` int(11) NOT NULL,
  \`requested_quantity\` int(11) NOT NULL,
  \`approved_quantity\` int(11) DEFAULT 0,
  PRIMARY KEY (\`id\`),
  KEY \`reservation_id\` (\`reservation_id\`),
  KEY \`item_id\` (\`item_id\`),
  CONSTRAINT \`lab_res_items_ibfk_1\` FOREIGN KEY (\`reservation_id\`) REFERENCES \`lab_reservations\` (\`id\`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT \`lab_res_items_ibfk_2\` FOREIGN KEY (\`item_id\`) REFERENCES \`lab_items\` (\`id\`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO \`lab_items\` (\`category_id\`, \`item_name\`, \`unit\`, \`total_quantity\`, \`available_quantity\`) VALUES
`;

let insertRows = [];

function parseQtyStr(str) {
    if(!str) return { qty: 0, unit: 'piece' };
    const clean = str.trim().toLowerCase();
    // try to extract number
    const match = clean.match(/^(\d+)(.*)$/);
    if(match) {
        let q = parseInt(match[1]);
        let u = match[2].trim() || 'piece';
        if(u.includes('set')) u = 'set';
        if(u.includes('pair')) u = 'pair';
        if(u.includes('pack')) u = 'pack';
        if(clean.includes('donation')) u = 'piece';
        return { qty: q, unit: u };
    }
    return { qty: 0, unit: 'piece' };
}

function processTable(data, categoryId, fallbackCategoryIdNameCheck) {
    data.forEach((row, index) => {
        if(index === 0) return; // skip header
        let itemName = row[0] ? row[0].trim() : '';
        if(!itemName || itemName.toLowerCase() === 'item' || itemName.toLowerCase() === 'linen') return;
        
        let qtyStrPrimary = row[1]; // Dec 2024 / Dec 2025
        let qtyStrSecondary = row[3]; // March 2025 / March 2026
        
        let primary = parseQtyStr(qtyStrPrimary);
        let secondary = parseQtyStr(qtyStrSecondary);
        
        let tQ = primary.qty;
        let aQ = tQ;
        
        // Items with 0 availability based on secondary column missing/zero
        if(secondary.qty === 0 && primary.qty > 0 && !qtyStrSecondary) {
             // Let's rely on specific names if we want, or just secondary.qty == 0
        }
        
        // Manual overwrite as mentioned "EXCEPT for items marked as 0 available like Peeler, Coffee Grinder, Pocket Thermometer"
        let nameLower = itemName.toLowerCase();
        if(nameLower.includes('peeler') || nameLower.includes('coffee grinder') || nameLower.includes('pocket thermometer')) {
            aQ = 0;
        } else if (secondary.qty === 0 && tQ > 0 && String(row[3]).trim() === '0') {
            aQ = 0;
        }

        if(tQ === 0 && aQ === 0) return; // Skip empty rows

        let finalCat = categoryId;
        if(fallbackCategoryIdNameCheck) {
            if(nameLower.includes('iron') || nameLower.includes('washing machine')) {
                finalCat = 5; // Laundry Tools
            } else {
                finalCat = 4; // Linens
            }
        }

        let escName = itemName.replace(/'/g, "\\'");
        let escUnit = primary.unit.replace(/'/g, "\\'");

        insertRows.push(`(${finalCat}, '${escName}', '${escUnit}', ${tQ}, ${aQ})`);
    });
}

processTable(hotKitchen, 1, false);
processTable(fbServices, 3, false);
processTable(laundry, 4, true);

sql += insertRows.join(",\n") + ";\n\nCOMMIT;";

fs.writeFileSync('database.sql', sql);
console.log('database.sql generated successfully.');
