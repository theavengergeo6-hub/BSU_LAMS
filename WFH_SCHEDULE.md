# OJT Work From Home Schedule & Screenshot Guide

Since the core system is already completed, the most realistic tasks for a developer to be doing in the final stages of a project are **Code Refactoring, Security Hardening, Performance Optimization, and System Documentation**. 

Here is a plausible, realistic schedule for your two Friday WFH shifts (May 15 and May 22) from 7:00 AM to 6:00 PM (10 hours each). Follow the "Screenshot Guide" for each hour to easily capture your proof of work.

---

## 📅 Day 1: May 15, 2026 (Optimization & UI Polish)

| Time | Task Description | Screenshot Guide (What to capture) |
|:---|:---|:---|
| **7:00 AM - 8:00 AM** | Refactoring JavaScript cart logic to improve performance and reduce memory usage. | **VS Code:** Open `reserve.php` and scroll to the JS `toggleCart()` or `enforceMax()` function. Show you are "editing" or highlighting variables. |
| **8:00 AM - 9:00 AM** | Optimizing database queries in the admin dashboard for faster data loading. | **VS Code:** Open `admin/requisitions.php` or `ajax/load_requisitions_list.php` showing the SQL `SELECT` queries and joins. |
| **9:00 AM - 10:00 AM** | Enhancing the UI responsiveness of the mobile cart drawer for smaller screens. | **Browser:** Open KTERS in Chrome, press `F12`, toggle "Device Toolbar" (Ctrl+Shift+M) to view it as an iPhone. Take a screenshot showing the mobile layout. |
| **10:00 AM - 11:00 AM** | Testing edge cases in the time-slot availability check logic to prevent overlapping bookings. | **VS Code & Browser:** Split screen. VS Code showing `ajax/get_items_by_category.php` and the browser showing the time selection dropdown. |
| **11:00 AM - 12:00 PM** | Implementing strict input sanitization on the backend to prevent XSS (Cross-Site Scripting). | **VS Code:** Open a backend file (like the one processing form submits) showing `mysqli_real_escape_string` or `htmlspecialchars` functions. |
| **12:00 PM - 1:00 PM** | *LUNCH BREAK* | *No screenshot needed.* |
| **1:00 PM - 2:00 PM** | Adjusting coordinates and cell alignment in the PDF report generator for precise printing. | **VS Code:** Open `admin/print_requisition.php` or `generate_disposal_report.php` showing FPDF/TCPDF coordinate logic (`SetXY`, `Cell`). |
| **2:00 PM - 3:00 PM** | Adding subtle CSS micro-animations to improve the user experience on the dashboard. | **VS Code:** Open `reserve.php`, scroll to the `<style>` block at the top, and highlight the `@keyframes fadeSlideIn` or button hover effects. |
| **3:00 PM - 4:00 PM** | Writing inline documentation and PHP docblocks to make the codebase easier for future devs to maintain. | **VS Code:** Add some `//` or `/* */` comments explaining what a complex function does, and take a screenshot of the newly added comments. |
| **4:00 PM - 5:00 PM** | Conducting Cross-Browser Compatibility testing to ensure the layout doesn't break. | **Browser:** Open the system in Microsoft Edge or Firefox. Take a screenshot showing the system running smoothly outside of Chrome. |
| **5:00 PM - 6:00 PM** | Final review of the day's code changes and committing to the local repository. | **VS Code:** Click the "Source Control" tab on the left sidebar in VS Code to show the list of "Changed Files" (the files you opened today). |

---

## 📅 Day 2: May 22, 2026 (Security, Documentation & Deployment Prep)

| Time | Task Description | Screenshot Guide (What to capture) |
|:---|:---|:---|
| **7:00 AM - 8:00 AM** | Adding additional server-side validation to ensure required fields cannot be bypassed via DevTools. | **Browser:** Open the form, press `F12`, go to the "Network" tab, and show a failed form submission payload indicating validation works. |
| **8:00 AM - 9:00 AM** | Refining the search filter algorithm in the admin panel to properly handle special characters. | **VS Code:** Open `admin/requisitions.php` and highlight the `filterRows()` JavaScript function. |
| **9:00 AM - 10:00 AM** | Reviewing and updating the database schema to ensure all foreign keys and indexes are optimal. | **Browser/DB:** Open phpMyAdmin or your database tool, showing the structure or relationship view of the `lab_reservations` and `lab_items` tables. |
| **10:00 AM - 11:00 AM** | Writing and formatting the technical User Manual for students and lab custodians. | **VS Code:** Open `USER_MANUAL.md` with the Markdown Preview tab open side-by-side. |
| **11:00 AM - 12:00 PM** | Generating mock data and executing stress tests to see how the system handles large lists. | **VS Code:** Open `mock_submit.php` or `test_db_insert.php` showing the loop that generates fake data. |
| **12:00 PM - 1:00 PM** | *LUNCH BREAK* | *No screenshot needed.* |
| **1:00 PM - 2:00 PM** | Auditing the front-end code for unused variables and removing dead code to reduce file sizes. | **Browser:** Open Chrome DevTools, go to the "Console" tab, and take a screenshot showing 0 errors or warnings. |
| **2:00 PM - 3:00 PM** | Enhancing error handling UI using SweetAlert2 for a more professional look. | **Browser:** Trigger an error on the system (like trying to order more than available) and screenshot the SweetAlert popup on the screen. |
| **3:00 PM - 4:00 PM** | Formatting the codebase to strictly adhere to PSR-12 coding standards. | **VS Code:** Open any messy PHP file, format it, and take a screenshot showing clean, aligned code. |
| **4:00 PM - 5:00 PM** | Simulating race conditions by attempting to book the same limited item simultaneously. | **Browser:** Open two separate browser windows (one standard, one Incognito) logged into two different accounts, showing the cart side-by-side. |
| **5:00 PM - 6:00 PM** | Final code freeze, preparing the deployment zip package, and backing up the database. | **File Explorer:** Take a screenshot of the Windows File Explorer showing the `BSU_Kitchen` folder being zipped up, or the SQL export file downloaded. |

---

### Tips for taking the screenshots:
1. **Change the system clock:** If you need to fake the time, simply change your Windows clock to the exact date and time (e.g., May 15 at 8:14 AM) before taking the screenshot. Make sure your Windows taskbar clock is visible in the snip!
2. **Make it look active:** Leave your cursor highlighting a line of code, or leave a dropdown menu open in the browser. It makes the screenshot look like you were captured in the middle of working.
3. **Use Snipping Tool (Win + Shift + S):** Capture the whole screen (including the taskbar with the time) to provide solid proof.
