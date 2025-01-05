# Protask - Project Management System

Protask is a modern web-based project management system that allows teams to collaborate effectively on projects. It provides different roles for project managers and team members, with features tailored to each role's responsibilities.

## Features

### For All Users
- Modern, responsive user interface with glassmorphism design
- Secure authentication system
- View public projects
- Contact form for support
- Detailed about section

### For Project Managers
- Create and manage projects
- Assign tasks to team members
- Track project progress
- Manage team members
- Generate project reports

### For Team Members
- Join existing projects
- View assigned tasks
- Update task status
- Collaborate with team members
- Track personal progress

## Technologies Used

- PHP
- MySQL
- HTML5
- Tailwind CSS
- Alpine.js
- Font Awesome Icons

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (for dependency management)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/protask.git
```

2. Navigate to the project directory:
```bash
cd protask
```

3. Configure your database:
   - Create a new MySQL database
   - Update the database configuration in `controller/classes/configDB.php`

4. Import the database schema:
   - Use the SQL file provided in the `database` directory

5. Configure your web server:
   - Point your web server to the project's root directory
   - Ensure proper permissions are set

## UML 

- [CLASS DIAGRAM](https://lucid.app/lucidchart/b54fc9dd-eda2-41ab-ad0b-fa50d95738ec/edit?viewport_loc=-4620%2C-1507%2C5657%2C2469%2C0_0&invitationId=inv_42ee7763-5de9-4d4c-8f75-cbd3c0ee038b)

- [use cases](https://lucid.app/lucidchart/223b6849-8f9e-40a4-8fec-3d8e6bafddf5/edit?invitationId=inv_a5de8098-12f5-4406-902a-512dd54a7f9b)


## Usage

1. Access the application through your web browser
2. Register a new account
3. Log in with your credentials
4. Based on your role (Project Manager/Team Member):
   - Project Managers can create and manage projects
   - Team Members can join projects and manage tasks

## Security Features

- Password hashing
- Session management
- Input validation
- XSS protection
- CSRF protection

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


## Acknowledgments

- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Font Awesome](https://fontawesome.com/)
