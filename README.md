# E-Commerce  (Laravel API)

## Requirements
- PHP 8+
- Composer
- MySQL


## Setup
1. clone  https://github.com/Ahmedhamad99/E-Commerce-backend.git
2. cp .env.example .env and set DB credentials
3. composer install
4. php artisan key:generate
5. php artisan jwt:secret
6. php artisan migrate
7. php artisan serve

## Auth (JWT)
- Register: POST /api/auth/register  {name, email, password, password_confirmation}
- Login: POST /api/auth/login {email, password}
- Include Authorization header for protected routes:
  Authorization: Bearer {token}

## Products
- GET /api/products
- POST /api/products {name, price, stock, description}
- PUT /api/products/{product}
- DELETE /api/products/{product}

## Cart & Orders
- GET /api/cart
- POST /api/cart {product_id, quantity}
- DELETE /api/cart/{product}
- POST /api/orders {address, phone}  -> validates stock, decreases stock, clears cart, returns order summary

## DB Diagram (simple)
- users (id, name, email, password, ...)
- products (id, name, description, price, stock, out_of_stock)
- cart_items (id, user_id, product_id, quantity)
- orders (id, order_number, user_id, address, phone, total)
- order_items (id, order_id, product_id, product_name, price, quantity, subtotal)

## Notes
- Use transactions in order creation to avoid race conditions.
- Product `out_of_stock` auto-updated when stock == 0.


## Digram

# users
 - id PK
 - name
 - email 
 - password
  

# products
 - id PK
 - name
 - price
 - stock
 - out_of_stock

# cart_items
 - id PK
 - user_id FK -> users.id
 - product_id FK -> products.id
 - quantity

# orders
 - id PK
 - order_number (unique)
 - user_id FK -> users.id
 - address
 - phone
 - total

# order_items
 - id PK
 - order_id FK -> orders.id
 - product_id FK -> products.id
 - product_name
 - price
 - quantity
 - subtotal
