<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


- [ ] Author: Mohamad Al Moazen
- [ ]  Email: mhd.moazen98@gmail.com

# Order Management & Payment API

- [ ] Note: 
> This documentation is intended to allow developers to integrate with the API without reading the source code.

Overview

This project is a RESTful API built with Laravel for managing products, orders, and payments.
It demonstrates clean architecture, secure authentication, extensible payment gateways, and test-driven development.
The system allows users to:

- Register and authenticate using JWT.
- Browse products.
- Create and manage orders.
- Process payments using configurable and extensible payment gateways.

Laravel is accessible, powerful, and proides tools required for large, robust applications.

## Tech Stack

- PHP version ^8.2
- Laravel version 12.50.0
- Composer version 2.8.11
- JWT Authentication (tymon/jwt-auth)
- MySQL / SQLite (testing)
- PHPUnit for Unit & Feature Tests

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Setup Instructions
1️⃣ Clone the repository:
- git clone https://github.com/Moazen98/moazen-order-management-assignment.git
- cd moazen-order-management-assignment

2️⃣ Install dependencies
- composer install

3️⃣ Environment setup
- cp .env.example to .env
```
php artisan key:generate
DB_CONNECTION=mysql
DB_DATABASE= YOUR_DB
DB_USERNAME= YOUR_USERNAME
DB_PASSWORD= YOUR_PASSWORD
```
4️⃣ JWT setup
- php artisan jwt:secret

5️⃣ Payment gateway configuration
```
PAYMENT_GATEWAY=paypal
PAYPAL_CLIENT_ID=paypal_client_id_here
PAYPAL_SECRET=paypal_secret_here
CREDIT_CARD_KEY =credit_card_key
CREDIT_CARD_SECRET =credit_card_secret_here
```
6️⃣ Run migrations
- Run migrations

7️⃣ Run seeders
- php artisan db:seed

8️⃣ Run tests
- have .env.testing
- php artisan test (have factories)

## Authentication
Authentication is handled using JWT.
- JWT is required for all order and payment operations and profile api.

## API Design (RESTful)
- The API follows RESTful principles:
- Resource-based URLs
- HTTP methods define actions
- Stateless authentication

## Payment Gateway Extensibility
- Allow adding new payment gateways without modifying existing business logic.
- Strategy Pattern (Core):
- Each payment gateway implements a common interface:
```
 interface PaymentGatewayInterface
  {
  public function pay(Order $order): array;
  }
  ```
- Examples:
  - PaypalGateway
  - PaypalGateway
- The system can easily support:
  - Stripe
  - Apple Pay
  - Bank Transfer

## Factory Pattern
Gateways are resolved using a factory:
- PaymentGatewayFactory::make('paypal');
- This avoids:
  - new Gateway() inside controllers
  - Hard-coded dependencies 

## Configuration
Gateways are configured via:
- .env
- config/payments.php

```
  return [
  'default' => env('PAYMENT_GATEWAY', 'paypal'),
  'gateways' => [
  'paypal' => [
  'client_id' => env('PAYPAL_CLIENT_ID'),
  'secret' => env('PAYPAL_SECRET'),
  ],
  ],
  ];
```
This makes the system:
- Secure
- Environment-based
- Easy to change without code changes

## Business Logic Architecture

1️⃣ Service Layer
- All business logic lives in Services:
  - OrderService
  - PaymentService
  - PaymentService
  
Controllers are kept thin.

2️⃣ Repository Pattern
Database access is abstracted via repositories:
 - OrderRepository
 - PaymentRepository
 - ProductRepository 

Benefits:
- Clean separation
- Easy testing
- Database-agnostic logic

3️⃣ Pipeline Pattern:

Complex validations (e.g., payment cancellation rules) are handled using Pipelines:

Example:

- PaymentExists
- PaymentNotSuccessful

This replaces complex if/else blocks with reusable rules.

4️⃣ Factory Pattern

Used in:

- Payment gateways
- Model factories for testing

5️⃣ Dependency Injection

- All services and repositories are injected via constructors or service container bindings.

6️⃣ Singleton & Bind

- For injection into the Ioc and managing the dependency life cycle

7️⃣ Form Request Validation

- Separate the validation logic from the controller and make it cleaner and more control for the error validation logic response

 Form Request Validation

8️⃣ Resource & Collection

- Customize the Response model for the frontend side

9️⃣ Helper functions:

- autoload helpers function that are reusable in different place in the code


🔟 Custom Response Json:

- Customize the response json structure for messaging and status code and return data 


#### you can check the postman Authorization section (Bearer token)
#### Also, you can switch the language by chaining the accept-language (en | ar)
## Other methods:
- Middlewares for Multiple Language and for secure the apis
- Easily expandable to multiple other languages for different model like: product model
- Seeders for inject data quickly into database
- RouteServiceProvider with prefix version like: /v1
- secure the data for each user that belong to him, prevent the other users from access to different one
- Translation response messages : validation message - response api message (ar-en)

## Notes:

> Note:
> Detailed request body examples for all endpoints can be reviewed using the provided Postman requests.
> These requests demonstrate the full payload structure, headers, and validation requirements for each API call.

>  You can check the postman file from the /app/developer_docs/moazen-order-management-assignment.postman_collection.json

#### Apis that need jtw token are as fellows:
- /authentication/logout
- /authentication/me
- /order/* (all the endpoints that belong to the /order prefix)
- /payment/* (all the endpoints that belong to the /payment prefix)

#### Apis that are public endpoints are as fellows:
- /products/*
- /login
- /register

