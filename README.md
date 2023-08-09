<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```yaml
openapi: 3.0.0
info:
  title: 'E-commerce api project'
  version: '1.0'
paths:
  /login:
    post:
      tags:
        - auth
      summary: 'Handle an incoming authentication request.'
      description: "Endpoint for login,\n    you should first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application"
      operationId: 42f77e072dec84b0e1094cca1228298e
      requestBody:
        content:
          application/json:
            schema:
              properties:
                email:
                  description: Email
                  type: string
                password:
                  description: Password
                  type: string
              type: object
              example:
                - '{"email": "test@mail.com", "password": "123456"}'
      responses:
        '204':
          description: 'Successful login'
        '422':
          description: 'These credentials do not match our records'
  /logout:
    post:
      tags:
        - auth
      summary: 'Destroy an authenticated session.'
      description: 'Endpoint for login'
      operationId: 03afb12b342c2ea43bdc1de4f39e91a8
      responses:
        '204':
          description: 'Successful logout'
      security:
        -
          scalar: sanctum
  /register:
    post:
      tags:
        - auth
      summary: 'Handle an incoming registration request.'
      description: "Endpoint for registration,\n    you should first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application"
      operationId: 573de1fed352c1205a32c4d1b9877375
      requestBody:
        content:
          application/json:
            schema:
              properties:
                first_name:
                  description: 'First name'
                  type: string
                last_name:
                  description: 'Last name'
                  type: string
                email:
                  description: Email
                  type: string
                password:
                  description: Password
                  type: string
              type: object
              example:
                - '{"first_name": "Name", "last_name": "Surname", "email": "test@mail.com", "password": "123456"}'
      responses:
        '204':
          description: 'Successful registration'
        '422':
          description: 'An error occurred during registration, the error message is attached'
  /api/cart:
    get:
      tags:
        - cart
      description: "Returns paginated list of products in logged user's cart,\n    total price of all products in cart, and last page number for pagination purposes"
      operationId: 1cc05750bf9c479c79f8b205f5a8bff4
      parameters:
        -
          name: page
          in: query
          description: 'Number of page for paginated list of products in cart'
          required: true
          allowEmptyValue: false
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
  /api/cart/add:
    post:
      tags:
        - cart
      description: "Endpoint for adding given amount of chosen product to logged user's cart"
      operationId: 8a051e160148a408fe92e1f8da586aab
      requestBody:
        content:
          application/json:
            schema:
              properties:
                product_id:
                  description: 'ID of product to be added'
                  type: string
                quantity:
                  description: 'Amount of product to add'
                  type: string
              type: object
              example:
                - '{"product_id": "1"}'
                - '{{"product_id": "1", "quantity": "3"}}'
      responses:
        '200':
          description: OK
        '500':
          description: 'Product is out of stock | The limit of products in the cart has been exceeded'
      security:
        -
          scalar: sanctum
  /api/cart/remove:
    post:
      tags:
        - cart
      description: "Endpoint for removing one piece of product from logged user's cart"
      operationId: cd194a560a0f1cc51d31831c3fad9a7d
      requestBody:
        content:
          application/json:
            schema:
              properties:
                product_id:
                  description: 'ID of product to be removed'
                  type: string
              type: object
              example:
                - '{"product_id": "1"}'
      responses:
        '200':
          description: OK
        '500':
          description: 'This product is no longer os your cart'
      security:
        -
          scalar: sanctum
  /api/catalog:
    get:
      tags:
        - catalog
      description: 'Returns paginated list of products and pagination metadata'
      operationId: bb01d72d9498739e6b5d1e6670e82a1c
      parameters:
        -
          name: page
          in: query
          description: 'Number of page for paginated list of products'
          required: true
          allowEmptyValue: false
        -
          name: category
          in: query
          description: 'ID of the searched category'
          required: false
          allowEmptyValue: true
        -
          name: text
          in: query
          description: 'Name of product to search for'
          required: false
          allowEmptyValue: true
          examples:
            'One word':
              summary: 'Search for a product whose name contains a given word'
              value: product
            'Multiple words':
              summary: "Search for a product whose name contains a given words separated by '_'"
              value: product_1
        -
          name: sort
          in: query
          description: 'Sorting option'
          required: false
          allowEmptyValue: true
          examples:
            Cheap:
              summary: 'Sorts products from cheap to expensive'
              value: cheap
            Expensive:
              summary: 'Sorts products from expensive to cheap'
              value: expensive
            Novelty:
              summary: 'Sorts products by newest'
              value: novelty
      responses:
        '200':
          description: OK
  /api/category:
    get:
      tags:
        - category
      description: ''
      operationId: 333075afb1071bc60ddc37709c6279b5
      parameters:
        -
          name: id
          in: query
          description: 'ID of category'
          required: true
          allowEmptyValue: false
      responses:
        '200':
          description: OK
        '500':
          description: 'No category with such id'
  /api/category/all:
    get:
      tags:
        - category
      description: 'List of all categories'
      operationId: 25bee80d5c542352be417a29e5028a6a
      responses:
        '200':
          description: OK
  /api/order/create:
    post:
      tags:
        - order
      description: 'Endpoint for creating order for logged user'
      operationId: 48fdd559c958da8e951390249c808a82
      requestBody:
        content:
          application/json:
            schema:
              properties:
                city:
                  description: 'City for products to be delivered to'
                  type: string
                date:
                  description: 'Date of delivery'
                  type: string
              type: object
              example:
                - '{"city": "Kyiv", "date": "2023-07-28"}'
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
  /api/order/cancel:
    post:
      tags:
        - order
      description: "Endpoint for cancelling logged user's order"
      operationId: 2e3c26b9746632826ee2b747807662dd
      requestBody:
        content:
          application/json:
            schema:
              properties:
                order_id:
                  description: 'ID of order to be canceled'
                  type: string
              type: object
              example:
                - '{"order_id": "1"}'
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
  /order/all:
    get:
      tags:
        - order
      description: 'List of paginated orders for logged user'
      operationId: 846dbc187977fb6d5860ec9d747f064e
      parameters:
        -
          name: page
          in: query
          description: 'Number of page for paginated list of products in cart'
          required: true
          allowEmptyValue: false
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
  /order/getProducts:
    get:
      tags:
        - order
      description: 'List of products in the order of logged user'
      operationId: f3cf0890a25c1a1b9d240f2509804396
      parameters:
        -
          name: order_id
          in: query
          description: 'ID of chosen order'
          required: true
          allowEmptyValue: false
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
  /api/product:
    get:
      tags:
        - product
      description: 'Returns product with given ID'
      operationId: b8cbbd4f5d5704256a9d8053465fedb3
      parameters:
        -
          name: id
          in: query
          description: 'ID of product'
          required: true
          allowEmptyValue: false
      responses:
        '200':
          description: OK
        '500':
          description: 'No product with such id'
  /api/user:
    get:
      tags:
        - user
      description: 'Returns currently logged user'
      operationId: 22ea85303059d4f2d15556c1c9fd65d2
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
  /api/pay:
    post:
      tags:
        - user
      description: ''
      operationId: 0f74dd3b764a371aa2a9f0beaa1d9d3a
      requestBody:
        content:
          application/json:
            schema:
              properties:
                money_amount:
                  description: 'The money amount by which the balance is replenished'
                  type: string
              type: object
              example:
                - '{"money_amount": "10000"}'
      responses:
        '200':
          description: OK
      security:
        -
          scalar: sanctum
tags:
  -
    name: auth
  -
    name: cart
  -
    name: catalog
  -
    name: category
  -
    name: order
  -
    name: product
  -
    name: user
