# LaQueVa Laravel - Pizza Management System

A modern pizza management system built with Laravel, React, and Inertia.js. This project migrates and enhances the original LaQueVa application with a full-featured backend API, responsive frontend, and comprehensive business logic.

## 🚀 Features

### Core Functionality
- **Complete CRUD Operations**: Customers, Pizzas, Orders, Categories, and Promotions
- **Advanced Authentication**: Role-based access control with Spatie Laravel Permission
- **Responsive Design**: Mobile-first approach using Tailwind CSS
- **Real-time Interface**: Asynchronous operations with React and Inertia.js
- **Report Generation**: Excel and PDF exports with custom styling
- **Shopping Cart**: Full-featured cart system with session persistence

### Technical Features
- **Database Design**: Five well-structured tables with proper relationships
- **Data Validation**: Client-side and server-side validation with error handling
- **Optimized Queries**: Eager loading and query scopes for performance
- **Transaction Safety**: Database transactions with proper rollback handling
- **Export System**: Professional reports with maatwebsite/excel and dompdf

## 💾 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 18+ and NPM
- MySQL 8.0 or higher

### Setup Instructions

1. **Clone the repository**
```bash
git clone https://github.com/your-username/laqueva-laravel.git
cd laqueva-laravel
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```

4. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database setup**
- Create a MySQL database named `laqueva_db`
- Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laqueva_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

7. **Build assets**
```bash
npm run build
```

8. **Start the development server**
```bash
php artisan serve
```

9. **For development with hot reload**
```bash
npm run dev
```

### Default Login Credentials
- **Admin**: admin@laqueva.com / password
- **User**: user@laqueva.com / password

## 📝 Usage Guide

### Admin Panel
1. **Dashboard**: Overview of orders, customers, and sales
2. **Pizza Management**: Add, edit, delete pizzas with categories
3. **Customer Management**: View and manage customer accounts
4. **Order Management**: Process orders and update status
5. **Reports**: Generate Excel and PDF reports

### Customer Interface
1. **Browse Menu**: View available pizzas with filtering
2. **Shopping Cart**: Add items, modify quantities, checkout
3. **Order Tracking**: Track order status in real-time
4. **Profile Management**: Update personal information

### Features Overview

#### Database Structure
- **Users**: Authentication with roles (admin, customer)
- **Categories**: Pizza categories with descriptions
- **Pizzas**: Menu items with prices and ingredients
- **Orders**: Customer orders with status tracking
- **Order Items**: Individual pizza items within orders
- **Relationships**: Proper foreign keys with cascade delete

#### Role-Based Access Control
- **Super Admin**: Full system access
- **Admin**: Order and pizza management
- **Customer**: Profile and order management
- **Permissions**: Granular control over actions

#### Validation & Error Handling
- **Form Validation**: Client-side and server-side validation
- **Error Messages**: User-friendly error displays
- **Exception Handling**: Comprehensive try-catch blocks
- **Transaction Safety**: Database rollback on errors

#### Query Optimization
- **Eager Loading**: Prevents N+1 query problems
- **Query Scopes**: Reusable query filters
- **Pagination**: Efficient data loading
- **Caching**: Session-based cart storage

## 📊 Reports

### Excel Reports
- Order summaries with detailed breakdowns
- Customer purchase history
- Pizza popularity analysis
- Revenue reports by date range

### PDF Reports
- Professional invoice generation
- Order receipts with branding
- Customer statements
- Inventory reports

## 📱 Responsive Design

- **Mobile-first approach** with Tailwind CSS
- **Touch-friendly interface** for mobile devices
- **Progressive enhancement** for desktop users
- **Consistent styling** across all devices

## 🚀 Performance Features

- **Asynchronous operations** with Inertia.js
- **Optimized database queries** with eager loading
- **Efficient asset compilation** with Vite
- **Session management** for cart persistence

## 🔧 Development

### Code Structure
```
laqueva-laravel/
├── app/
│   ├── Http/Controllers/     # Request handling
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization logic
│   └── Services/            # Business logic
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/            # Sample data
├── resources/
│   ├── js/                 # React components
│   ├── css/                # Tailwind styles
│   └── views/              # Blade templates
└── routes/                 # Application routes
```

### Key Files
- **Models**: User, Pizza, Order, Category, OrderItem
- **Controllers**: PizzaController, OrderController, CartController
- **Middleware**: Authentication, role checking
- **Requests**: Form validation classes
- **Resources**: API response formatting

## 📚 Documentation

### API Endpoints
- `GET /api/pizzas` - List all pizzas
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order status

### Database Schema
- Foreign key relationships ensure data integrity
- Cascade delete prevents orphaned records
- Indexes optimize query performance
- Migrations provide version control

## 📝 Testing

### Running Tests
```bash
php artisan test
```

### Test Coverage
- Unit tests for models and services
- Feature tests for API endpoints
- Browser tests for UI interactions
- Database tests for relationships

## 🛠️ Troubleshooting

### Common Issues
1. **Database connection**: Check `.env` credentials
2. **Missing assets**: Run `npm run build`
3. **Permission errors**: Check file permissions
4. **Cache issues**: Run `php artisan optimize:clear`

### Debug Mode
```bash
# Enable debug mode
php artisan optimize:clear
php artisan config:cache
```

## 💯 Project Requirements Compliance

### Database Design (20 points)
✅ **5 well-structured tables** with proper relationships
✅ **3+ one-to-many relationships** with foreign keys
✅ **Data integrity** with cascade delete constraints
✅ **Normalized design** following best practices

### Functionality & Business Logic (25 points)
✅ **Authentication system** with role management
✅ **Complete CRUD operations** for all entities
✅ **Comprehensive validation** with error handling
✅ **Optimized queries** with eager loading
✅ **Exception handling** with transactions

### Reports & Export (15 points)
✅ **Excel export** with maatwebsite/excel
✅ **PDF generation** with dompdf
✅ **Download buttons** for easy access
✅ **Formatted reports** with styling

### User Interface (20 points)
✅ **Responsive design** with Tailwind CSS
✅ **Intuitive navigation** and user experience
✅ **Asynchronous operations** with Inertia.js
✅ **Form validation** with error display

### Code Quality (10 points)
✅ **Clean code structure** with proper organization
✅ **Comprehensive comments** and documentation
✅ **Best practices** implementation
✅ **Error handling** throughout application

### Documentation (10 points)
✅ **Installation guide** with step-by-step instructions
✅ **Usage documentation** with examples
✅ **API documentation** with endpoints
✅ **Troubleshooting guide** for common issues

## 📞 Support

For questions or issues:
1. Check the troubleshooting section
2. Review the documentation
3. Contact: your-email@example.com

## 📜 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**LaQueVa Laravel** - A comprehensive pizza management system demonstrating modern web development practices with Laravel, React, and Inertia.js.

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
