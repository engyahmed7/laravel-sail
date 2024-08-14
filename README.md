# Laravel Project with Firebase Integration

This application leverages Laravel Sail for development, integrates Firebase for authentication and notifications, and includes Filament for an advanced admin panel. It supports JWT and Sanctum for API authentication and provides API documentation with Swagger and Scribe.

## Overview

This project demonstrates a robust setup combining modern technologies and practices:

- **Laravel Sail** for a streamlined development environment.
- **Firebase** for secure user authentication and real-time notifications.
- **Filament** for a powerful and intuitive admin panel with relation management.
- **JWT and Sanctum** for flexible and secure API authentication.
- **Swagger and Scribe** for detailed API documentation.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP**: Version 8.1
- **Composer**: Dependency manager for PHP
- **Node.js**: For managing JavaScript dependencies
- **Docker**: Required for Laravel Sail
- **Firebase Account**: For authentication and notifications setup

## Installation

Follow these steps to get your development environment up and running:

1. **Clone the Repository**

   ```bash
   git clone https://github.com/engyahmed7/laravel-sail.git
   cd laravel-sail
   ```

2. **Set Up Laravel Sail**

   Start Laravel Sail:

   ```bash
   ./vendor/bin/sail up
   ```

3. **Install PHP and JavaScript Dependencies**

   - **PHP Dependencies**

     ```bash
     ./vendor/bin/sail composer install
     ```

   - **JavaScript Dependencies**

     ```bash
     ./vendor/bin/sail npm install
     ```

4. **Configure the Environment**

   - Copy the `.env.example` file to `.env`:

     ```bash
     cp .env.example .env
     ```

   - Update the `.env` file with your Firebase credentials and other configuration settings.

## Firebase Integration

This project integrates with Firebase for authentication and notifications:

1. **Authentication**

   - **Email/Password**: Set up in the Firebase console.
   - **Google Sign-In**: Configure in the Firebase console.
   - **Mobile Phone**: Enable phone authentication in Firebase and configure SMS verification.

2. **Notifications**

   - Configure Firebase Cloud Messaging (FCM) to handle push notifications.

## Authentication

1. **JWT Authentication**

   - JWT is used for secure API access. Ensure you have the JWT secret configured and set up for your API routes.

2. **Sanctum Authentication**

   - Sanctum is used for API token authentication. Install and configure Sanctum to secure your API routes.

## Filament Integration

Filament provides a powerful admin panel for managing your application's data:

1. **Install Filament**

   Use Composer to install Filament:

   ```bash
   ./vendor/bin/sail composer require filament/filament
   ```

2. **Set Up Filament**

   Publish Filament assets and configuration:

   ```bash
   ./vendor/bin/sail artisan filament:install
   ```

3. **Relation Managers**

   - Filament includes relation managers for managing `Posts`, `Categories`, `Users`, and `Comments`.

## API Documentation

1. **Swagger Integration**

   - Install Swagger for API documentation:

     ```bash
     ./vendor/bin/sail composer require zircote/swagger-php
     ```

   - **Generate Swagger Documentation**

     Run the following command to generate Swagger documentation:

     ```bash
     ./vendor/bin/sail artisan swagger-lume:generate
     ```

2. **Scribe Documentation**

   - Install Scribe for generating API documentation:

     ```bash
     ./vendor/bin/sail composer require scribe/scribe
     ```

   - Generate the API documentation:

     ```bash
     ./vendor/bin/sail artisan scribe:generate
     ```

## Middleware

To secure your routes, a middleware is created to use Firebase authentication. This ensures that only authenticated users can access protected routes.

## Notifications

Firebase Cloud Messaging (FCM) is used for sending push notifications. Set up FCM in your Firebase console and integrate it with your application to send notifications to users.

## Usage

1. **Start the Application**

   Use Laravel Sail to start your application:

   ```bash
   ./vendor/bin/sail up
   ```

2. **Access the Application**

   Open your browser and navigate to `http://localhost` to use the application.

3. **Admin Panel**

   Access Filament’s admin panel to manage `Posts`, `Categories`, `Users`, and `Comments` via the admin interface.

4. **API Endpoints**

   - Refer to the Swagger documentation available at `http://localhost/api/documentation` for details on API endpoints and their usage.

   - Refer to the Scribe documentation available at `http://localhost/docs` for details on API endpoints and their usage. 

## References

- [Laravel Documentation](https://laravel.com/docs)
- [Firebase Documentation](https://firebase.google.com/docs)
- [JWT Authentication](https://github.com/tymondesigns/jwt-auth)
- [Laravel Sanctum](https://laravel.com/docs/9.x/sanctum)
- [Filament Documentation](https://filamentphp.com/docs)
- [Swagger Documentation](https://swagger.io/docs/)
- [Scribe Documentation](https://scribe.readthedocs.io/en/latest/)
