# BSU Laboratory Asset Management System (BSU-LAMS) User Manual

Welcome to the **BSU Laboratory Asset Management System (BSU-LAMS)**. This manual provides a step-by-step guide on how to navigate and use the system effectively, whether you are a student requesting equipment or an administrator managing the laboratory.

---

## Table of Contents

### 1. INTRODUCTION
1.1 [System Overview](#11-system-overview)  
1.2 [Key Features](#12-key-features)  
1.3 [Who Can Use This System](#13-who-can-use-this-system)  

### 2. FOR STUDENTS: HOW TO REQUEST EQUIPMENT
2.1 [Browsing Available Equipment](#21-browsing-available-equipment)  
2.2 [Checking Availability](#22-checking-availability)  
2.3 [Step-by-Step Requisition Guide](#23-step-by-step-requisition-guide)  
2.4 [After Submitting Your Requisition](#24-after-submitting-your-requisition)  
2.5 [Checking Your Requisition Status](#25-checking-your-requisition-status)  

### 3. FOR ADMINISTRATORS / STAFF: REQUISITION MANAGEMENT
3.1 [Logging In](#31-logging-in)  
3.2 [Dashboard Overview](#32-dashboard-overview)  
3.3 [Managing Pending Requisitions](#33-managing-pending-requisitions)  
3.4 [Step-by-Step Status Update Guide](#34-step-by-step-status-update-guide)  
3.5 [Automated Features & Cooldowns](#35-automated-features--cooldowns)  
3.6 [Managing Inventory (Equipment Logs)](#36-managing-inventory-equipment-logs)  
3.7 [Changing Settings & Passwords](#37-changing-settings--passwords)  

---

## 1. INTRODUCTION

### 1.1 System Overview
BSU-LAMS is a comprehensive Laboratory Asset Management System designed specifically for the BSU Kitchen Laboratory. It streamlines the entire process of borrowing equipment, tracking inventory, and managing laboratory sessions through a digitized workflow.

### 1.2 Key Features
*   **Real-time Inventory Tracking**: Instant visibility of available kitchen tools and equipment.
*   **Automated Requisition Forms**: Automated PDF generation of official requisition forms.
*   **Time-Aware Scheduling**: Prevents overbooking by checking availability against specific dates and time slots.
*   **3-Hour Cooldown System**: Automatically manages equipment "resting" periods for cleaning and inspection.
*   **Disposal Reporting**: Identifies aging equipment (3+ years) for replacement or decommissioning.

### 1.3 Who Can Use This System
*   **Students**: To browse equipment and submit borrowing requests for their laboratory classes.
*   **Instructors**: To monitor equipment usage and verify student requisitions.
*   **Laboratory Staff / Admins**: To manage inventory, approve requests, and maintain laboratory records.

---

## 2. FOR STUDENTS: HOW TO REQUEST EQUIPMENT

### 2.1 Browsing Available Equipment
Navigate to the **Home Page** or **Catalog** to see the full list of available equipment categorized by their usage (e.g., Hot Kitchen Tools, Food & Beverage Service).

> [!NOTE]
> **[Insert Screenshot of Catalog/Home Page Here]**

### 2.2 Checking Availability
The system automatically hides or marks items as "Unavailable" if they are already booked for your chosen time slot. You can see the real-time stock count on each item card.

### 2.3 Step-by-Step Requisition Guide
1.  **Select Items**: Click the "Add to Request" button on the items you need.
2.  **Open Request Form**: Go to the Reservation/Request page.
3.  **Fill Details**: Provide your Subject, Course/Section, and the purpose of the lab.
4.  **Set Time**: Choose the Date and the Start/End times for your session.
5.  **Submit**: Click "Submit Requisition."

> [!NOTE]
> **[Insert Screenshot of Step-by-Step Reservation Guide / Form Filling Here]**

### 2.4 After Submitting Your Requisition
Once submitted, your request is sent to the Laboratory Staff for approval. You will see a "Success" message and your requisition will appear in your history as **Pending**.

### 2.5 Checking Your Requisition Status
Go to **My Reservations** to track the status of your requests:
*   `Pending`: Awaiting staff review.
*   `Approved`: Your request is accepted. You can pick up the items at the scheduled time.
*   `Ongoing`: You currently have the equipment in your possession.
*   `Completed`: Equipment has been returned and checked.

> [!NOTE]
> **[Insert Screenshot of Reservation Status List Here]**

---

## 3. FOR ADMINISTRATORS / STAFF: REQUISITION MANAGEMENT

### 3.1 Logging In
Access the Admin Portal via the `/admin` URL. Use your official administrator credentials to log in.

### 3.2 Dashboard Overview
The Dashboard provides an immediate overview of today's activities, including pending approvals, active laboratory sessions, and stock alerts.

> [!NOTE]
> **[Insert Screenshot of Admin Dashboard Overview Here]**

### 3.3 Managing Pending Requisitions
All new requests appear in the **Requisitions** section. Staff should review the items requested and the scheduled time before approving.

### 3.4 Step-by-Step Status Update Guide
1.  **Approve**: Once verified, click "Approve."
2.  **Release**: When the student picks up the gear, mark it as "Ongoing."
3.  **Return**: When items are returned, click "Complete." This triggers the **3-hour cooldown**.

> [!NOTE]
> **[Insert Screenshot of Status Update Buttons/Process Here]**

### 3.5 Automated Features & Cooldowns
The system handles stock restoration automatically. After a request is "Completed," the items remain unavailable for 3 hours for cleaning. The system's **Cron Job** will restore the stock to the available pool automatically after the time expires.

### 3.6 Managing Inventory (Equipment Logs)
Administrators can add new equipment, update quantities, or decommissioning old items. Every manual adjustment is recorded in the **Item Logs** for accountability.

> [!NOTE]
> **[Insert Screenshot of Inventory Management & Logs Here]**

### 3.7 Changing Settings & Passwords
Navigate to **Settings** to update laboratory operating hours, manage user roles, or change your administrator password.

---

> [!TIP]
> **Pro Tip:** Use the "Print" feature for Approved requests to generate a PDF form that students can sign upon equipment release!
