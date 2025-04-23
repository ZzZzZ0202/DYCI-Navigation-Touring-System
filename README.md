eto yung sa student Accoung:
2023-00001
dyci2023

eto sa Admin:
admin
admin123


# DYCI Touring System

A web-based touring system for Don Bosco Technical Institute.

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP (recommended for local development)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/DYCI_Touring_System.git
```

2. Set up the database:
   - Open XAMPP Control Panel
   - Start Apache and MySQL services
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `dyci_touring_system`
   - Import the database schema from `sql/database.sql`

3. Configure the database connection:
   - Open `config/database.php`
   - Update the database credentials if needed

4. Place the project in your web server's root directory:
   - For XAMPP: `C:/xampp/htdocs/DYCI_Touring_System`

5. Access the application:
   - Open your web browser
   - Navigate to `http://localhost/DYCI_Touring_System`

## Features

- Student and Visitor Management
- Room Scheduling
- Building Information
- Campus Map
- Admin Dashboard
- User Authentication

## Project Structure

- `admin/` - Admin-related files
- `assets/` - CSS, JavaScript, and other static files
- `config/` - Configuration files
- `database/` - Database-related files
- `images/` - Image assets
- `includes/` - Common PHP includes
- `sql/` - Database schema and queries

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

