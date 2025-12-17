# Post and Comments System

A web-based application for managing posts and their associated comments, built using PHP and MySQL. The system features a user-facing front office for viewing and interacting with posts, and an administrative back office for managing content.

## Features

### Front Office
- View posts by category
- Add new posts
- Add comments (messages) to posts
- Update and delete posts and comments
- Browse different subjects and threads

### Back Office
- Administrative dashboard using SB Admin 2 theme
- Manage posts and comments
- View statistics and analytics
- Responsive design with Bootstrap

## Technologies Used

- **Backend**: PHP 7+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Frameworks/Libraries**:
  - Bootstrap 4 (for responsive design)
  - SB Admin 2 (admin theme)
  - jQuery
  - Chart.js (for data visualization)
  - DataTables (for table management)
  - Font Awesome (icons)
- **Build Tools**: Gulp (for asset compilation)

## Prerequisites

- XAMPP (or similar Apache/MySQL/PHP stack)
- MySQL database
- Web browser

## Installation

1. **Clone or Download** the project to your `htdocs` directory:
   ```
   Place the "Post and Comments" folder in C:\xampp\htdocs\
   ```

2. **Database Setup**:
   - Start XAMPP and ensure Apache and MySQL are running
   - Create a database named `posts` in phpMyAdmin
   - Import the database schema (if provided) or create tables manually:

   **Posts Table**:
   ```sql
   CREATE TABLE posts (
       id INT PRIMARY KEY,
       subject VARCHAR(255),
       content TEXT,
       number_messages INT DEFAULT 0,
       user_name VARCHAR(100),
       category VARCHAR(100),
       view_count INT DEFAULT 0,
       like_count INT DEFAULT 0,
       created_at DATETIME
   );
   ```

   **Messages Table**:
   ```sql
   CREATE TABLE messages (
       id INT PRIMARY KEY,
       post_id INT,
       content TEXT,
       number_replies INT DEFAULT 0,
       user_name VARCHAR(100),
       like_count INT DEFAULT 0,
       created_at DATETIME,
       FOREIGN KEY (post_id) REFERENCES posts(id)
   );
   ```

3. **Configuration**:
   - Update database credentials in `config.php` if necessary (default: localhost, root, no password)

4. **Back Office Assets** (if needed):
   - Navigate to `View/BackOffice/`
   - Run `npm install` to install dependencies
   - Run `npm start` to compile SCSS and watch for changes

## Usage

1. **Access the Application**:
   - Front Office: `http://localhost/Post%20and%20Comments/View/FrontOffice/`
   - Back Office: `http://localhost/Post%20and%20Comments/View/BackOffice/`

2. **Front Office Navigation**:
   - Browse posts on the main page
   - Use forms to add new posts and comments
   - View threads and subjects

3. **Back Office Management**:
   - Login to admin panel (implement authentication as needed)
   - Manage posts and comments
   - View dashboard with charts and statistics

## Project Structure

```
Post and Comments/
├── config.php                 # Database configuration
├── Controller/
│   ├── PostController.php     # Post CRUD operations
│   └── messagescontroller.php # Message CRUD operations
├── Model/
│   ├── Post.php              # Post model class
│   └── messages.php          # Message model class
└── View/
    ├── BackOffice/           # Admin interface
    │   ├── Post-backoffice.php
    │   ├── css/
    │   ├── js/
    │   ├── scss/
    │   └── vendor/           # Third-party libraries
    └── FrontOffice/          # User interface
        ├── addmessages.php
        ├── addpost.php
        ├── posts.html
        ├── css/
        ├── js/
        └── lib/              # Frontend libraries
```

## Contributing

This is a module work project. For improvements:
1. Follow the MVC architecture
2. Maintain code consistency
3. Test database operations thoroughly
4. Ensure responsive design

## License

This project is for educational purposes.