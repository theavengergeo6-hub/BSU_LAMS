# Development Journal: BSU Kitchen Laboratory Requisition System

| Day | Date | Activity |
|:---:|:---|:---|
| 1 | Apr 20, Mon | Designed the student requisition layout with a sticky sidebar for selected items. |
| 2 | Apr 21, Tue | Built the equipment grid with category tabs for easy browsing of kitchen tools. |
| 3 | Apr 22, Wed | Coded the `loadCategory` function to fetch equipment items via AJAX. |
| 4 | Apr 23, Thu | Implemented the "Add to Cart" button for individual equipment items. |
| 5 | Apr 27, Mon | Developed the `toggleCart` logic to manage the list of selected lab tools. |
| 6 | Apr 28, Tue | Created the "Select Size" modal to handle equipment variants like different pot sizes. |
| 7 | Apr 29, Wed | Built the mobile-friendly cart drawer for reviewing selections on small screens. |
| 8 | Apr 30, Thu | Added real-time quantity validation to prevent ordering more than what is in stock. |
| 9 | May 04, Mon | Designed the Student Information section with fields for course and section details. |
| 10 | May 05, Tue | Implemented the Subject and Station selection form in the requisition page. |
| 11 | May 06, Wed | Coded the time-slot picker with automatic end-time calculation for lab sessions. |
| 12 | May 07, Thu | Integrated the `reloadItemsForTimeslot` trigger to update availability when the date changes. |
| 13 | May 11, Mon | Developed the `submitReservation` function to send requisition data to the server. |
| 14 | May 12, Tue | Created the admin dashboard view for managing pending laboratory requests. |
| 15 | May 13, Wed | Implemented the "Review" modal for admins to inspect student requisition carts. |
| 16 | May 14, Thu | Added real-time status updates so students can track their approved requests. |
| 17 | May 18, Mon | Coded the stock restoration logic for denied or completed requisitions. |
| 18 | May 19, Tue | Implemented the 3-hour cooldown window for equipment after a lab session ends. |
| 19 | May 20, Wed | Developed the PDF report generator for official laboratory requisition forms. |
| 20 | May 21, Thu | Fixed database column errors in the inventory and transaction log system. |
| 21 | May 25, Mon | Refined the breakage report layout to match the official department template. |
| 22 | May 26, Tue | Added fixed coordinates to the PDF generator for precise form alignment. |
| 23 | May 27, Wed | Implemented the monthly breakage aggregation feature for asset management. |
| 24 | May 28, Thu | Renamed "Reservations" to "Requisitions" across all files and UI components. |
| 25 | Jun 01, Mon | Added AJAX polling to the admin dashboard for real-time requisition updates. |
| 26 | Jun 02, Tue | Polished the CSS using modern typography and a premium red color palette. |
| 27 | Jun 03, Wed | Conducted final end-to-end testing of the student requisition and admin approval flow. |
| 28 | Jun 04, Thu | Updated the user manual with step-by-step instructions for lab custodians. |
