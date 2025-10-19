# 🎓 School Management System

<p align="center">
  <img src="public/images/logo.svg" alt="School Management System Logo" width="120">
</p>

<p align="center">
  <strong>A comprehensive, modern school management solution built with Laravel</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.33.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2.12-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📋 Table of Contents

- [About](#-about)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [User Roles](#-user-roles)
- [Screenshots](#-screenshots)
- [Database Schema](#-database-schema)
- [API Documentation](#-api-documentation)
- [Contributing](#-contributing)
- [License](#-license)
- [Support](#-support)

---

## 📖 About

**School Management System** is a comprehensive web-based application designed to streamline and automate school operations. Built with Laravel 12 and modern web technologies, it provides an intuitive interface for managing students, teachers, classes, attendance, examinations, fees, library, and much more.

### 🎯 Project Goals

- Digitalize school administration processes
- Improve communication between stakeholders
- Provide real-time analytics and reporting
- Ensure data security and privacy
- Create a user-friendly experience for all roles

---

## ✨ Features

### 🏫 Core Modules

#### 📚 Student Management
- Student registration and profile management
- Online admission form with document upload
- Roll number and admission number generation
- Student ID card generation
- Bulk student import/export
- Student promotion and transfer
- Academic history tracking

#### 👨‍🏫 Teacher & Staff Management
- Teacher profile management
- Subject and class assignments
- Workload management
- Performance tracking
- Teacher ID card generation
- Leave management system
- Salary structure and payroll

#### 📖 Academic Management
- Class and section management
- Subject management with elective options
- Timetable generation (manual and auto)
- Academic year management
- Curriculum management for Classes 1-10
- Multiple academic groups (Science, Humanities, Commerce)

#### ✅ Attendance System
- Daily attendance marking
- Student attendance tracking
- Teacher attendance management
- Attendance reports (daily, monthly, yearly)
- Leave request management
- SMS/Email notifications for absences

#### 📝 Examination & Grading
- Exam schedule management
- Marks entry and management
- Grade calculation system
- Report card generation (PDF)
- Progress tracking
- Result publication
- Mark sheets and transcripts

#### 💰 Finance & Accounts
- Fee structure management
- Fee collection tracking
- Online payment integration ready
- Receipt generation
- Scholarship management
- Expense tracking
- Invoice management
- Financial reports (income, expense, balance)
- Fee defaulter tracking
- Student ledger

#### 📚 Library Management
- Book cataloging with ISBN
- Book issue and return
- Fine calculation for overdue books
- Digital library support
- Book reservation system
- Barcode scanning support
- Library reports and statistics

#### 💬 Communication System
- Internal messaging system
- Announcements and notices
- Event management
- Notice board
- Parent-teacher communication
- Email notifications
- SMS integration ready

#### 🎨 Additional Features
- News management
- Photo gallery
- Contact form management
- Complaint/feedback system
- Dashboard with analytics
- Role-based access control
- Activity logs
- Advanced search
- Data export (CSV, Excel, PDF)

### 👥 Multi-Role Portal

#### Admin Portal
- Complete system control
- User management
- Reports and analytics
- System settings
- Data backup

#### Teacher Portal
- Class and subject management
- Attendance marking
- Marks entry
- Student progress tracking
- Class materials upload
- Assignment management
- Communication tools

#### Student Portal
- Personal dashboard
- Attendance view
- Exam results
- Timetable
- Assignments
- Fee status
- Library books issued

#### Parent Portal
- Child's progress tracking
- Attendance monitoring
- Exam results view
- Fee payment
- Teacher communication
- Event calendar

#### Accountant Portal
- Fee management
- Payment processing
- Financial reports
- Invoice generation

#### Librarian Portal
- Book management
- Issue/return tracking
- Fine management
- Library reports

---

## 🛠 Tech Stack

### Backend
- **Framework:** Laravel 12.33.0
- **PHP Version:** 8.2.12
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Authorization:** Spatie Laravel Permission

### Frontend
- **CSS Framework:** Tailwind CSS
- **JavaScript:** Alpine.js
- **Icons:** Heroicons
- **Charts:** Chart.js (ready to integrate)
- **Build Tool:** Vite

### Additional Libraries
- **PDF Generation:** DomPDF
- **Excel Export:** Laravel Excel (ready to integrate)
- **Image Processing:** Intervention Image (ready to integrate)
- **Queue Management:** Laravel Queue

---

## 📦 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Torikul-048/School_Management_System.git
   cd School_Management_System
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Create environment file**
   ```bash
   copy .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure database**
   
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=school_management
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Build assets**
   ```bash
   npm run build
   ```

10. **Start development server**
    ```bash
    php artisan serve
    ```

11. **Access the application**
    
    Open your browser and visit: `http://localhost:8000`

---

## ⚙️ Configuration

### Default Admin Credentials

After running seeders, use these credentials to login:

- **Email:** admin@school.com
- **Password:** password123

> ⚠️ **Security:** Change default password immediately after first login!

### File Storage

Configure your file storage in `.env`:

```env
FILESYSTEM_DISK=public
```

### Mail Configuration

Set up email service (Gmail, Mailgun, etc.):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourschool.com
MAIL_FROM_NAME="School Management System"
```

### Queue Configuration

For background job processing:

```bash
php artisan queue:work
```

---

## 👥 User Roles

### 1. Admin (Super Admin)
- **Full System Access**
- User management
- All CRUD operations
- System settings
- Reports and analytics

### 2. Teacher
- Class and subject management
- Attendance marking
- Marks entry
- Student progress tracking
- Communication with students/parents

### 3. Student
- View personal information
- Check attendance
- View exam results
- Access assignments
- View timetable

### 4. Parent
- Monitor child's progress
- View attendance and results
- Communicate with teachers
- Pay fees online

### 5. Accountant
- Fee management
- Payment processing
- Financial reports
- Invoice generation

### 6. Librarian
- Book management
- Issue/return tracking
- Fine collection
- Library reports

---

## 📸 Screenshots

### Public Website
![Homepage](docs/screenshots/homepage.png)
*Modern, responsive homepage with news ticker and information sections*

### Admin Dashboard
![Admin Dashboard](docs/screenshots/admin-dashboard.png)
*Comprehensive dashboard with real-time analytics and quick actions*

### Student Management
![Student Management](docs/screenshots/student-management.png)
*Easy-to-use student management interface*

### Attendance System
![Attendance](docs/screenshots/attendance.png)
*Quick and efficient attendance marking system*

### Report Card
![Report Card](docs/screenshots/report-card.png)
*Professional report card generation*

---

## 🗃 Database Schema

### Key Tables

- **users** - System users with authentication
- **students** - Student information
- **teachers** - Teacher details
- **classes** - Class information
- **sections** - Class sections
- **subjects** - Subject details
- **attendances** - Daily attendance records
- **exams** - Examination details
- **marks** - Student marks
- **fee_structures** - Fee configuration
- **fee_collections** - Fee payment records
- **books** - Library books
- **book_issues** - Book issue records
- **contacts** - Contact form submissions
- **announcements** - School announcements
- **events** - School events
- **notices** - Notice board items

### ER Diagram

```
[View complete ER diagram](docs/database-schema.png)
```

---

## 📚 API Documentation

### Authentication

All API endpoints require authentication via Laravel Sanctum tokens.

### Endpoints

```
GET    /api/students           - List all students
POST   /api/students           - Create new student
GET    /api/students/{id}      - Get student details
PUT    /api/students/{id}      - Update student
DELETE /api/students/{id}      - Delete student

GET    /api/attendance         - Get attendance records
POST   /api/attendance         - Mark attendance

GET    /api/exams              - List exams
GET    /api/results/{student}  - Get student results
```

[View complete API documentation](docs/API.md)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Style

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add comments for complex logic
- Update documentation

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 💬 Support

### Get Help

- 📧 **Email:** support@schoolsystem.com
- 🐛 **Issues:** [GitHub Issues](https://github.com/Torikul-048/School_Management_System/issues)
- 📖 **Documentation:** [Wiki](https://github.com/Torikul-048/School_Management_System/wiki)

### Useful Links

- [Installation Guide](INSTALLATION.md)
- [User Manual](docs/USER_MANUAL.md)
- [FAQ](docs/FAQ.md)
- [Changelog](CHANGELOG.md)

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Spatie Laravel Permission
- All open-source contributors

---

## 📊 Project Status

- ✅ Core Features: Complete
- ✅ Student Management: Complete
- ✅ Teacher Management: Complete
- ✅ Attendance System: Complete
- ✅ Examination Module: Complete
- ✅ Finance Module: Complete
- ✅ Library Module: Complete
- ✅ Communication System: Complete
- 🔄 Mobile App: In Progress
- 📋 Advanced Analytics: Planned

---

<p align="center">
  <strong>Made with ❤️ for Better Education Management</strong>
</p>

<p align="center">
  <sub>© 2025 School Management System. All rights reserved.</sub>
</p>
