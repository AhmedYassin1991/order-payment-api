# Order and Payment Management API

This is a Laravel-based API for managing orders and payments. It supports multiple payment gateways and follows RESTful principles.

---

## Table of Contents
1. [Setup Instructions](#setup-instructions)
2. [Payment Gateway Extensibility](#payment-gateway-extensibility)
3. [API Documentation](#api-documentation)
4. [Testing](#testing)
5. [Assumptions](#assumptions)

---

## Setup Instructions

Follow these steps to set up the project locally:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/order-payment-api.git
   cd order-payment-api

2. **Install dependencies**:

  composer install

3. **Set up the environment file**:
   
   cp .env.example .env
   Update the database credentials in .env:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your-DataBase-Name
    DB_USERNAME=your-username
    DB_PASSWORD=your-password

4.**Generate application key**:
php artisan key:generate

5.**Generate JWT secret key**
php artisan jwt:secret

**Payment Gateway Extensibility**
The system is designed to easily add new payment gateways. Follow these steps to add a new gateway:

1.Create a new gateway class:

Create a new class in app/Services/PaymentGateways that implements PaymentGatewayInterface.

Example: StripeGateway.php.

2.Register the gateway:

Add the gateway to the config/payment.php file:

return [
    'default_gateway' => env('DEFAULT_PAYMENT_GATEWAY', 'credit_card'),
    'gateways' => [
        'credit_card' => \App\Services\PaymentGateways\CreditCardGateway::class,
        'paypal' => \App\Services\PaymentGateways\PayPalGateway::class,
        'stripe' => \App\Services\PaymentGateways\StripeGateway::class,
    ],
];
3.Update environment variables (if needed):
STRIPE_API_KEY=sk_test_1234567890
4.Test the new gateway:

Use the PaymentService to process payments with the new gateway.


**API Documentation**
The API is documented using a Postman collection. Follow these steps to use it:

1.Import the Postman collection:

 Import the order-payment-api.postman_collection.json file into Postman.

2.Set up the environment:

 Create an environment named Order Payment API with a variable auth_token.

3.Authenticate:

 Run the Login request to authenticate and set the auth_token environment variable.

4.Use the token:

Use the {{auth_token}} variable in the Authorization header for all requests.

**Testing**
To run the tests, use the following command:

php artisan test

Test Coverage
Unit Tests:

Payment gateways (e.g., CreditCardGatewayTest, PayPalGatewayTest).

Feature Tests:

Order management (e.g., create, update, delete orders).

Payment processing (e.g., process payment, view payments).

Assumptions
Payments can only be processed for orders in the confirmed status.

Orders cannot be deleted if they have associated payments.

The API uses JWT for authentication.


order-payment-api/
├── app/
├── config/
├── database/
├── tests/
├── routes/
├── .env.example
├── README.md
├── order-payment-api.postman_collection.json
├── composer.json
├── composer.lock
└── ...


---

### **Summary**
- The `README.md` file provides clear setup instructions, explains how to add new payment gateways, and includes API documentation.
- The Postman collection is included for easy testing and integration.
- The repository structure is clean and well-organized.

Let me know if you need further assistance!





  


