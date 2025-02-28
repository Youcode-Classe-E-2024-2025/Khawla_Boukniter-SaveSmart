# SaveSmart - Personal Finance Management System

SaveSmart is a robust financial management application built with Laravel, designed to help individuals and families track their finances effectively.

## Features

### User Management
- Personal and Family account types
- Secure authentication system
- Profile customization
- Family member invitations via unique codes

### Transaction Management
- Income and expense tracking
- Custom category creation
- Detailed transaction history
- Family-wide transaction visibility

### Budget Goals
- Goal setting and tracking
- Progress monitoring
- Category-based goals
- Family shared goals

### Financial Analytics
- Monthly income vs expenses charts
- Category distribution visualization
- Dynamic data representation
- Personal/Family statistics separation

### Categories
- Custom category creation
- Default categories provided
- Category-based expense tracking
- Family-shared categories

## Technical Stack
- Laravel Framework
- MySQL Database
- Tailwind CSS
- Chart.js for visualizations

## Installation

1. Clone the repository
```bash
git clone https://github.com/Youcode-Classe-E-2024-2025/Khawla_Boukniter-SaveSmart.git
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database
```bash
php artisan migrate
```

5. Start the application
```bash
php artisan serve
```

## Usage

1. Register an account (Personal or Family)
2. Set up your profile and preferences
3. Start tracking transactions
4. Create and monitor financial goals
5. View analytics and reports

## Project Structure

- `app/` - Core application logic
- `resources/views/` - Blade templates
- `routes/` - Application routes
- `database/migrations/` - Database structure
- `public/` - Public assets

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request
