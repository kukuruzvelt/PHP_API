# E-commerce API Project

**Version**: 1.0

## Overview

The E-commerce API Project is a Laravel-based API that powers an e-commerce platform. It utilizes a MySQL database to store data and leverages Laravel Sanctum for authentication and authorization, ensuring secure access to its features.

## Features

- **User Authentication and Authorization**: Laravel Sanctum is used to implement robust user authentication and authorization, ensuring that only authorized users can access protected endpoints.

- **User Registration**: Users can easily register for an account, providing their first name, last name, email, and password.

- **Shopping Cart Management**: The API allows users to manage their shopping carts by adding and removing products. Users can view their cart contents and check out when ready.

- **Product Catalog**: Users can browse a catalog of products, search for specific products by name, and filter products by category. Sorting options include by price (cheap to expensive), expensive to cheap, and by novelty.

- **Order Creation**: Users can create orders for the products in their shopping cart, specifying the delivery city and date.

- **Order Management**: Users can view a list of their orders, including the products in each order. They also have the option to cancel orders.

- **Category Information**: Users can retrieve information about specific product categories or get a list of all available categories.

- **User Profile**: Users can retrieve information about their own profiles.

- **Balance Replenishment**: Users can replenish their balance with a specified money amount.

## Installation

To use this API, follow these steps:

1. Clone the repository.
2. Configure the `.env` file with your database credentials.
3. Run database migrations and seeders.
4. Set up Laravel Sanctum for authentication.

## Authentication with Laravel Sanctum

To authenticate your Single Page Application (SPA) with Laravel Sanctum, follow these steps:

1. **Initialize CSRF Protection**: Your SPA's "login" page should make a request to the `/sanctum/csrf-cookie` endpoint to initialize CSRF protection for the application. During this request, Laravel will set an `XSRF-TOKEN` cookie containing the current CSRF token.

2. **Pass CSRF Token in Headers**: On subsequent requests, ensure that you pass the CSRF token in an `X-XSRF-TOKEN` header. Some popular HTTP client libraries like Axios and Angular HttpClient automatically handle this for you by reading the token from the `XSRF-TOKEN` cookie and setting it in the header. If your JavaScript HTTP library does not do this automatically, you will need to manually set the `X-XSRF-TOKEN` header to match the value of the `XSRF-TOKEN` cookie set by the `/sanctum/csrf-cookie` route.

By following these steps, you can properly authenticate and secure your SPA while using the E-commerce API Project.
