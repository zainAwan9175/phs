Smart Lab - University Lab Equipment Booking + Usage Tracking System

Quick start:
1. Copy this folder to your XAMPP `htdocs` (already located here).
2. Start Apache and MySQL from XAMPP control panel.
3. You can either create the database manually and import `database/schema.sql`, or run the built-in seeder:

	- Open in your browser: http://localhost/smartlab/run_seed.php

	This will create the database, tables, default roles and an admin user (admin@smartlab.local / admin123) and seed labs + equipment.
5. Place this project in `http://localhost/smartlab` and open in browser.

Default admin user (seed): admin@smartlab.local / password: admin123 (change after first login)

Notes:
- Images are stored as base64 in DB in this initial scaffold. For production, consider storing files on disk.
