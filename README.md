# Starr-Starr

## Description
Starr-Starr is a safe web platform designed for children to learn and interact. Developed in PHP using the MVC (Model-View-Controller) model, it includes features for educational quizzes, a points and badges system, news, posts, comments, and reports. Parents can create accounts to monitor their children's activities, and teachers can add content and quizzes to facilitate learning.

## Features
- Points and badges system to reward users.
- Management of news and associated comments.
- Posts and messages with interactions.
- Reporting system for moderation.
- Educational quizzes added by teachers.
- Parent accounts for monitoring children's activities.
- Teacher accounts for content creation and teaching.
- Admin dashboard for managing points, transactions, and moderation.
- Front office interface for end users.

## Prerequisites
- PHP 7.4 or higher
- Web server (Apache recommended, via XAMPP)
- MySQL database
- Modern web browser

## API Architecture
-Submitting reports
-Fetching messages or responses
-Handling dynamic content updates without page reload
-Supporting future mobile or external integrations

## Installation
1. Clone the repository into the XAMPP htdocs directory:
   ```
   git clone <repository-url> c:\xampp\htdocs\Starr-Starr-main
   ```
2. Configure the database in `config/config.php` and `config/email_config.php`.
3. Import the database schema if provided.
4. Start XAMPP and access `http://localhost/Starr-Starr-main`.

## Usage
- Access the front office interface via the home page.
- Admins can log in to the back office to manage content.
- Use forms to publish news, posts, or submit reports.

## Tests
Run unit tests via PHPUnit if configured:
```
phpunit
```
Or manually test features in the interface.

## Contribution
1. Fork the project.
2. Create a branch for your changes.
3. Submit a pull request with a clear description.

## License
This project is under the MIT license. See the LICENSE file for more details.

## Authors
- Iheb Hamouda
- Mounib Sarray
- Jihene Jeridi
- Ahmed Safierrahman Abidi
- Guesmi Tasnim
- Nasralli Houda

## Acknowledgments
- Thanks to the open source community for the libraries used.
- Inspired by educational projects in web programming.
