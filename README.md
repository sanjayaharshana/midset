# Midset - Salary Sheet Management System

A comprehensive Laravel-based salary sheet management system designed for tracking promoter attendance, payments, and coordinator management.

## 🚀 Features

### Core Functionality
- **Job Management**: Create and manage promotional jobs with start/end dates
- **Promoter Management**: Track promoter details, positions, and contact information
- **Salary Sheet Creation**: Generate salary sheets with attendance tracking
- **Attendance Tracking**: Daily attendance recording with automatic calculations
- **Coordinator Management**: Assign coordinators and track coordination fees
- **Client Management**: Manage client companies and their details

### Advanced Features
- **Position-wise Salary Rules**: Set different salary rates for different promoter positions
- **Dynamic Attendance Columns**: Automatically generate attendance columns based on job dates
- **SweetAlert2 Integration**: Modern, beautiful alerts and confirmations
- **AJAX Form Submission**: Smooth, non-blocking form submissions
- **Responsive Design**: Works on desktop and mobile devices
- **Role-based Access Control**: Admin and user role management

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Bootstrap 5, SweetAlert2
- **Database**: MySQL/PostgreSQL
- **JavaScript**: Vanilla JS with Fetch API
- **CSS**: Custom CSS with Bootstrap integration

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web Server (Apache/Nginx)

## 🔧 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/midset.git
cd midset
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=midset
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

## 📊 Database Structure

### Main Tables
- `users` - System users and authentication
- `clients` - Client companies
- `custom_jobs` - Promotional jobs
- `promoters` - Promoter information
- `promoter_positions` - Available promoter positions
- `coordinators` - Coordinator details
- `salary_sheet` - Main salary sheet records
- `employers_salary_sheet_item` - Individual promoter salary items
- `position_wise_salary_rules` - Salary rules per position

### Key Relationships
- Jobs have multiple salary sheets
- Salary sheets contain multiple promoter items
- Promoters belong to positions
- Coordinators manage promoters

## 🎯 Usage

### Creating a Salary Sheet
1. **Select a Job**: Choose from available promotional jobs
2. **Add Promoters**: Select promoters and fill in their details
3. **Record Attendance**: Enter daily attendance (0/1) for each promoter
4. **Set Allowances**: Add food, accommodation, and other allowances
5. **Assign Coordinators**: Select coordinators and set coordination fees
6. **Save**: Submit the salary sheet for processing

### Attendance Tracking
- **Automatic Date Generation**: Attendance columns are created based on job start/end dates
- **Fallback System**: If no job is selected, 6 default attendance columns are created
- **Real-time Calculations**: Attendance totals and amounts are calculated automatically

### Position-wise Salary Rules
- Set different salary rates for different promoter positions
- Rules are applied automatically when creating salary sheets
- Easy management through the admin interface

## 🔐 Authentication & Authorization

The system includes:
- **User Registration/Login**: Standard Laravel authentication
- **Role Management**: Admin and regular user roles
- **Permission System**: Granular permission control using Spatie Laravel Permission

## 📱 API Endpoints

### Salary Sheets
- `GET /admin/salary-sheets` - List all salary sheets
- `POST /admin/salary-sheets` - Create new salary sheet
- `GET /admin/salary-sheets/{id}` - Show specific salary sheet
- `PUT /admin/salary-sheets/{id}` - Update salary sheet
- `DELETE /admin/salary-sheets/{id}` - Delete salary sheet

### Jobs
- `GET /admin/jobs` - List all jobs
- `POST /admin/jobs` - Create new job
- `GET /admin/jobs/{id}` - Show specific job

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📈 Performance Features

- **Optimized Queries**: Efficient database queries with proper indexing
- **Lazy Loading**: Images and assets are loaded on demand
- **Caching**: Laravel caching for improved performance
- **AJAX Submissions**: Non-blocking form submissions

## 🔧 Configuration

### Environment Variables
Key configuration options in `.env`:
```env
APP_NAME="Midset Salary Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=midset
DB_USERNAME=root
DB_PASSWORD=

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
```

## 🚀 Deployment

### Production Deployment
1. **Set Environment**: Change `APP_ENV=production` in `.env`
2. **Optimize**: Run `php artisan optimize`
3. **Build Assets**: `npm run production`
4. **Configure Web Server**: Point document root to `public/` directory
5. **Set Permissions**: Ensure proper file permissions for storage and cache

### Docker Deployment (Optional)
```bash
docker-compose up -d
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Support

For support and questions:
- Create an issue on GitHub
- Contact: your-email@example.com

## 🔄 Changelog

### Version 1.0.0
- Initial release
- Basic salary sheet management
- Attendance tracking
- Coordinator management
- SweetAlert2 integration
- Responsive design

## 🎉 Acknowledgments

- Laravel Framework
- Bootstrap for UI components
- SweetAlert2 for beautiful alerts
- Spatie Laravel Permission for role management

---

**Made with ❤️ for efficient salary management**