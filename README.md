# Import Users Test Task

**Import users**, a Laravel-based web application for uploading user's records by Excel sheet file.

## Test Task Overview

This task is used to uploading user's records by Excel sheet file and show the uploaded user's data in a table.

## Features

- We can upload Excel sheet with the help of Upload button.

- After uploaded the Excel sheet we can Upload the profile photo with the help of Upload button in the profile photo column, Once the photo will be shown in the UI

- You can check the list of users with the help of User List button

- To avoid the bulk Imports, the maximum number of rows per Excel file is limited to 100

## Getting Started

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Abdul291095/Sports.git

2. **Install Dependencies:**

   ```bash
   composer install

3. **Configure Environment:**

- Create a copy of the .env.example file and name it .env. Configure your database connection.

4. **Generate Application Key:**

    ```bash
    php artisan key:generate

5. **Run the Development Server:**

   ```bash
    php artisan serve

- The application should now be accessible at http://127.0.0.1:8000.
