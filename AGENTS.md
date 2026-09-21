# AGENTS.md --- Tikona Project Context

> **Purpose:** This file is the shared project context for both the
> developer and AI assistant working on Tikona.\
> Before changing database logic, controllers, API routes, Blade views,
> or frontend behavior, use this document as the baseline so the
> implementation stays consistent.

------------------------------------------------------------------------

## 1. Project Identity

**Project:** Tikona Coffee Ordering Platform

Tikona is a coffee-shop platform for the Tikona brand. The core customer
flow is:

``` text
Landing / Home
    ↓
Browse coffee products
    ↓
Choose product
    ↓
Create order
    ↓
Choose order type
    ├── Dine In
    └── Takeaway
    ↓
Payment
    ↓
Payment verification / Midtrans
    ↓
Transaction status
    ↓
Order completed
```

The platform has two main user roles:

-   `admin` --- staff/admin who manages products, categories,
    transactions, and payments.
-   `customer` --- normal user who browses products, creates orders,
    pays, and writes reviews.

The application is being developed with **Laravel 13**, using Laravel's
MVC architecture, Eloquent ORM, Blade views, API routes, and
authentication/API capabilities.

------------------------------------------------------------------------

# 2. Core Architecture

Tikona should be understood as having two related application surfaces:

### A. Web / Blade

Used for pages that users see directly.

``` text
Browser
   ↓
Web Route
   ↓
Controller
   ↓
Model / Database
   ↓
Controller prepares data
   ↓
Blade View
   ↓
HTML
```

Example:

``` text
GET /
    ↓
HomeController@index
    ↓
Product + Category + Review/Sales data
    ↓
resources/views/index.blade.php
```

### B. REST API

Used for JSON-based communication, including Postman testing and
frontend/API clients.

``` text
Client / Postman
   ↓
API Route
   ↓
Controller
   ↓
Validation / Authorization
   ↓
Model / Database
   ↓
JSON Response
```

Example:

``` text
GET /api/products
    ↓
ProductController@index
    ↓
Product model
    ↓
JSON response
```

### Important distinction

A **Controller is not automatically an API**.

A controller is application logic that receives a request and produces a
response.

The route determines how the controller is exposed:

``` php
Route::get('/', [HomeController::class, 'index']);
```

is a web page route.

Whereas:

``` php
Route::get('/products', [ProductController::class, 'index']);
```

inside `routes/api.php` is an API endpoint.

The same controller method can conceptually prepare data, but web
controllers normally return a View while API controllers normally return
JSON.

------------------------------------------------------------------------

# 3. MVC Responsibilities

## Model

Models represent database entities and relationships.

Current core models:

``` text
User
Category
Product
Transaction
TransactionDetail
Payment
Review
```

Models should contain:

-   database relationships
-   casts
-   model-level behavior
-   reusable query relationships/scopes where appropriate

Do not put large HTML fragments inside models.

Example:

``` php
class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
```

------------------------------------------------------------------------

## View

Blade views are responsible for presentation.

Views should:

-   display data
-   format simple values
-   render UI states
-   render loops
-   handle simple conditional presentation

Views should NOT be responsible for:

-   complex database queries
-   calculating business-critical totals
-   deciding authorization rules
-   directly manipulating database records

Prefer:

``` php
$product->name
$product->price
$product->category->name
```

over database queries directly inside Blade.

------------------------------------------------------------------------

## Controller

Controllers coordinate requests and application behavior.

A controller should generally:

1.  Receive the request.
2.  Validate input where appropriate.
3.  Authorize the action where appropriate.
4.  Retrieve or modify data.
5.  Prepare the response.
6.  Return a View, redirect, or JSON response.

Avoid putting all application logic into one giant controller.

------------------------------------------------------------------------

# 4. Database Structure

Tikona currently has **7 core tables**:

``` text
users
categories
products
transactions
transaction_details
payments
reviews
```

------------------------------------------------------------------------

## 4.1 users

``` text
id
name
email
password
role
created_at
updated_at
```

Constraints:

``` text
email = unique
role = enum('admin', 'customer')
role default = customer
```

Relationships:

``` text
User 1:N Transaction
User 1:N Review
```

------------------------------------------------------------------------

## 4.2 categories

``` text
id
name
created_at
updated_at
```

Relationship:

``` text
Category 1:N Product
```

------------------------------------------------------------------------

## 4.3 products

``` text
id
category_id
name
description
price
image
is_available
created_at
updated_at
```

Important:

-   `category_id` references `categories.id`.
-   `price` is the current product price.
-   `image` stores a relative storage path, not a full URL.
-   `is_available` controls whether the product can currently be
    ordered.

Relationship:

``` text
Product N:1 Category
Product 1:N TransactionDetail
Product 1:N Review
```

Example image value:

``` php
'image' => 'products/americano.png'
```

------------------------------------------------------------------------

## 4.4 transactions

A transaction represents an order.

``` text
id
user_id
order_type
total_price
status
created_at
updated_at
```

Enums:

``` text
order_type:
- dine_in
- takeaway

status:
- pending
- confirmed
- completed
- cancelled
```

Relationships:

``` text
Transaction N:1 User
Transaction 1:N TransactionDetail
Transaction 1:1 Payment
```

------------------------------------------------------------------------

## 4.5 transaction_details

Represents individual products inside an order.

``` text
id
transaction_id
product_id
quantity
price
subtotal
created_at
updated_at
```

Important:

`transaction_details.price` is the historical product price at the time
of purchase.

Do NOT replace it with the current `products.price` when displaying
historical orders.

Relationship:

``` text
TransactionDetail N:1 Transaction
TransactionDetail N:1 Product
```

Business calculation:

``` text
subtotal = price × quantity
```

------------------------------------------------------------------------

## 4.6 payments

``` text
id
transaction_id
midtrans_order_id
payment_method
amount
status
paid_at
created_at
updated_at
```

Important:

-   `transaction_id` references the local transaction.
-   `midtrans_order_id` is an external Midtrans identifier.
-   `midtrans_order_id` is NOT a foreign key.
-   Payment belongs to one transaction.

Relationship:

``` text
Payment 1:1 Transaction
```

------------------------------------------------------------------------

## 4.7 reviews

``` text
id
user_id
product_id
rating
description
created_at
updated_at
```

`rating` is intended to be between:

``` text
1 - 5
```

Relationships:

``` text
Review N:1 User
Review N:1 Product
```

------------------------------------------------------------------------

# 5. Entity Relationship Overview

``` text
User
 ├── 1:N Transactions
 └── 1:N Reviews

Category
 └── 1:N Products
              ├── 1:N TransactionDetails
              └── 1:N Reviews

Transaction
 ├── N:1 User
 ├── 1:N TransactionDetails
 └── 1:1 Payment

TransactionDetail
 ├── N:1 Transaction
 └── N:1 Product

Payment
 └── 1:1 Transaction

Review
 ├── N:1 User
 └── N:1 Product
```

------------------------------------------------------------------------

# 6. Order and Payment Concept

The correct conceptual separation is:

``` text
Transaction = order
TransactionDetail = items inside the order
Payment = payment record for the order
```

Example:

``` text
Transaction #1001
order_type = dine_in
status = confirmed
total_price = 65000

    ├── Americano
    │   quantity = 2
    │   price = 18000
    │   subtotal = 36000
    │
    └── V60
        quantity = 1
        price = 28000
        subtotal = 28000
```

Payment then belongs to Transaction #1001.

------------------------------------------------------------------------

# 7. Best Seller Concept

Products do not have a permanent `sold_count` column.

Sales are derived from:

``` text
transaction_details.quantity
```

Therefore, a best seller should normally be calculated using:

``` text
SUM(transaction_details.quantity)
```

grouped by product.

This means:

``` text
Americano
5 transactions
2 + 1 + 3 + 1 + 2
= 9 units sold
```

is different from counting unique customers.

### Units sold

``` text
SUM(quantity)
```

### Number of unique buyers

Conceptually:

``` text
COUNT(DISTINCT transaction.user_id)
```

For the Tikona homepage, **best seller currently means products with the
highest number of units sold**, not the highest number of unique
customers.

Cancelled orders should not contribute to best-seller calculations.

A suitable production query can restrict transaction status to
successful/relevant states such as:

``` text
confirmed
completed
```

depending on the final business rule.

------------------------------------------------------------------------

# 8. Current Home Page Data Concept

The homepage is a page-level composition rather than a generic product
CRUD page.

Recommended architecture:

``` text
HomeController
    ↓
Prepare homepage data
    ↓
index.blade.php
```

The homepage may need:

-   best-selling products
-   product category
-   average review rating
-   availability
-   promotional content
-   testimonials/reviews
-   other homepage-specific information

The ProductController should remain focused on product resources/API
behavior.

------------------------------------------------------------------------

# 9. View Data Contract

Before modifying a Blade view, identify exactly what data the controller
provides.

Example:

``` php
return view('index', [
    'bestSellers' => $bestSellers,
]);
```

Then Blade should use:

``` php
@foreach ($bestSellers as $product)
    {{ $product->name }}
@endforeach
```

Do not silently invent variables such as:

``` php
$products
$menus
$items
$bestProducts
```

unless the controller actually provides them.

### Naming rule

Use names that describe their purpose:

``` text
$bestSellers
$product
$categories
$transaction
$payment
$reviews
```

Avoid ambiguous names such as:

``` text
$data
$result
$item
$object
```

when more descriptive names are practical.

------------------------------------------------------------------------

# 10. Blade View Rules

Blade should primarily be presentation-oriented.

Good:

``` php
{{ $product->name }}

{{ number_format($product->price, 0, ',', '.') }}

@if ($product->is_available)
    Available
@else
    Unavailable
@endif
```

Acceptable for simple display formatting:

``` php
{{ Str::limit($product->description, 70) }}
```

Avoid:

``` php
Product::where(...)->get()
```

inside Blade.

Avoid complex calculations such as determining transaction totals inside
the view.

Calculate important business values before passing them to the view.

------------------------------------------------------------------------

# 11. Product Image Storage

Product images use Laravel public storage.

Database value:

``` text
products/americano.png
```

Physical file:

``` text
storage/app/public/products/americano.png
```

Public symbolic link:

``` text
public/storage
```

Create the link with:

``` bash
php artisan storage:link
```

Blade:

``` php
<img
    src="{{ $product->image
        ? asset('storage/' . $product->image)
        : 'fallback-image-url'
    }}"
    alt="{{ $product->name }}"
>
```

### Correct

``` php
'image' => 'products/americano.png'
```

### Incorrect

``` php
'image' => 'storage/products/americano.png'
```

because the Blade implementation already adds:

``` text
storage/
```

Final URL:

``` text
/storage/products/americano.png
```

------------------------------------------------------------------------

# 12. Current Product Seed Data

The current seed concept contains 5 products:

``` text
Americano
Cafe Latte
Tikona Aren Coffee
V60
Caramel Macchiato
```

Current image paths:

``` text
products/americano.png
products/cafe-latte.png
products/tikona-aren.png
products/v60.png
products/caramel-macchiato.png
```

Categories include:

``` text
Coffee
Signature
Manual Brew
```

The exact category IDs should always be obtained from the created
category models rather than hardcoded assumptions.

Example:

``` php
'category_id' => $coffee->id
```

------------------------------------------------------------------------

# 13. API Route Structure

Current API resources include:

## Authentication

``` text
POST /api/register
POST /api/login
POST /api/logout
GET  /api/me
```

## Profile

``` text
GET /api/profile
PUT /api/profile
```

## Products

``` text
GET    /api/products
GET    /api/products/{product}

POST   /api/admin/products
PUT    /api/admin/products/{product}
DELETE /api/admin/products/{product}
```

## Categories

``` text
GET    /api/categories
GET    /api/categories/{category}

POST   /api/admin/categories
PUT    /api/admin/categories/{category}
DELETE /api/admin/categories/{category}
```

## Transactions

``` text
GET /api/transactions
POST /api/transactions
GET /api/transactions/{transaction}

GET /api/admin/transactions
GET /api/admin/transactions/{transaction}
PUT /api/admin/transactions/{transaction}/status
```

## Payments

``` text
POST /api/payments
GET  /api/payments/{payment}
GET  /api/admin/payments/{payment}
POST /api/payments/midtrans/notification
```

## Reviews

``` text
GET    /api/products/{product}/reviews
POST   /api/products/{product}/reviews
PUT    /api/reviews/{review}
DELETE /api/reviews/{review}
```

------------------------------------------------------------------------

# 14. API vs Web Route

Web page:

``` php
Route::get('/', [HomeController::class, 'index']);
```

Expected response:

``` text
HTML / Blade View
```

API:

``` php
Route::get('/products', [ProductController::class, 'index']);
```

Expected response:

``` text
JSON
```

When creating an API endpoint, do not return a Blade view.

When creating a normal Blade page, do not unnecessarily turn the page
into a JSON endpoint.

------------------------------------------------------------------------

# 15. API Response Concept

A normal API response should be predictable.

For example:

``` json
{
    "message": "Products retrieved successfully",
    "data": []
}
```

For validation errors, use Laravel's validation mechanisms and return
appropriate HTTP status codes.

Typical conceptual status codes:

``` text
200 = successful read/update
201 = successful creation
204 = successful deletion with no body
400 = malformed/general bad request when appropriate
401 = unauthenticated
403 = authenticated but unauthorized
404 = resource not found
422 = validation error
500 = unexpected server error
```

Do not expose sensitive exception details to normal API clients.

------------------------------------------------------------------------

# 16. Validation Rules

Validate all user-controlled input.

Examples:

### Product

``` text
name          required|string|max:255
description   nullable|string
price         required|numeric|min:0
category_id   required|exists:categories,id
image         nullable|image
is_available  boolean
```

### Review

``` text
rating        required|integer|min:1|max:5
description   nullable|string
```

### Transaction

Validate:

``` text
order_type
products/items
product IDs
quantity
```

Do not trust a price sent by the browser.

The server should obtain the current product price from the database and
calculate:

``` text
subtotal = database price × quantity
```

Then calculate:

``` text
total_price = SUM(subtotals)
```

------------------------------------------------------------------------

# 17. Security Principles

Tikona should follow Laravel's built-in security mechanisms rather than
trying to manually solve everything.

## SQL Injection

Use Eloquent/query builder bindings.

Prefer:

``` php
Product::where('id', $id)->first();
```

over manually concatenating SQL strings.

Never build SQL like:

``` php
DB::select("SELECT * FROM products WHERE id = $id");
```

with unsanitized user input.

------------------------------------------------------------------------

## XSS

Blade's normal output syntax:

``` php
{{ $value }}
```

escapes HTML output.

Avoid unescaped output:

``` php
{!! $value !!}
```

unless the content is explicitly trusted and sanitized.

Product descriptions, review descriptions, user names, etc. should be
treated as untrusted input.

------------------------------------------------------------------------

## CSRF

Laravel web forms should use:

``` blade
@csrf
```

API authentication/CSRF behavior should follow the application's
Sanctum/API architecture.

Do not disable CSRF protection just to make a request work.

------------------------------------------------------------------------

## Authentication

Protected operations should require authentication.

Examples:

``` text
Creating transaction
Writing review
Updating profile
Viewing personal transactions
Creating payment
Admin product management
Admin category management
Admin transaction management
```

------------------------------------------------------------------------

## Authorization

Authentication answers:

``` text
"Who are you?"
```

Authorization answers:

``` text
"Are you allowed to do this?"
```

For example:

``` text
customer → cannot manage products
customer → cannot change another customer's transaction
admin → can manage products/categories/transactions
```

Do not rely only on hiding admin buttons in Blade.

Authorization must also be enforced server-side.

------------------------------------------------------------------------

# 18. Important Transaction Security Rule

Never trust client-side financial values.

The browser may send:

``` json
{
    "product_id": 1,
    "quantity": 3,
    "price": 1000
}
```

The server should NOT trust:

``` text
price = 1000
```

Instead:

``` text
product_id → database
             ↓
current product price
             ↓
price × quantity
             ↓
subtotal
             ↓
total
```

This prevents clients from manipulating prices.

The same principle applies to payment amounts.

------------------------------------------------------------------------

# 19. Model Relationship Convention

Expected relationships:

### User

``` php
hasMany(Transaction::class)
hasMany(Review::class)
```

### Category

``` php
hasMany(Product::class)
```

### Product

``` php
belongsTo(Category::class)
hasMany(TransactionDetail::class)
hasMany(Review::class)
```

### Transaction

``` php
belongsTo(User::class)
hasMany(TransactionDetail::class)
hasOne(Payment::class)
```

### TransactionDetail

``` php
belongsTo(Transaction::class)
belongsTo(Product::class)
```

### Payment

``` php
belongsTo(Transaction::class)
```

### Review

``` php
belongsTo(User::class)
belongsTo(Product::class)
```

------------------------------------------------------------------------

# 20. Eager Loading

When a view needs related data, prefer eager loading to avoid
unnecessary repeated queries.

Example:

``` php
Product::with('category')->get();
```

For product reviews:

``` php
Product::with('reviews')->get();
```

For transactions:

``` php
Transaction::with([
    'user',
    'transactionDetails.product',
    'payment',
])->get();
```

Think about the data a page needs before querying it.

------------------------------------------------------------------------

# 21. Homepage Best Seller Query Concept

Recommended conceptual query:

``` php
$bestSellers = Product::query()
    ->with('category')
    ->withAvg('reviews', 'rating')
    ->withSum([
        'transactionDetails as total_sold' => function ($query) {
            $query->whereHas('transaction', function ($query) {
                $query->whereIn('status', ['confirmed', 'completed']);
            });
        }
    ], 'quantity')
    ->where('is_available', true)
    ->orderByDesc('total_sold')
    ->take(4)
    ->get();
```

Required relationship:

``` php
Product::transactionDetails()
```

and:

``` php
TransactionDetail::transaction()
```

This is a page-specific query and belongs conceptually in
`HomeController`, not directly in the Blade view.

------------------------------------------------------------------------

# 22. Homepage UI Context

The Tikona homepage is designed as a modern coffee-brand website.

Current visual direction:

``` text
Primary color: #E29C23
Font: Urbanist
Style: Simple + Modern
```

The homepage/landing page should communicate:

-   Tikona coffee brand
-   product discovery
-   dine-in ordering
-   takeaway ordering
-   best sellers
-   coffee/product information
-   social proof/reviews
-   clear calls to action

The UI should remain consistent with the Tikona brand rather than
looking like a generic admin dashboard.

------------------------------------------------------------------------

# 23. Suggested View Organization

Conceptually:

``` text
resources/views/
│
├── layouts/
│   └── app.blade.php
│
├── index.blade.php
│
├── products/
│   ├── index.blade.php
│   └── show.blade.php
│
├── order/
│   └── ...
│
├── profile/
│   └── ...
│
├── transactions/
│   ├── index.blade.php
│   └── show.blade.php
│
└── admin/
    ├── products/
    ├── categories/
    ├── transactions/
    └── payments/
```

The exact structure can evolve, but keep customer-facing pages separate
from admin pages.

------------------------------------------------------------------------

# 24. View Component Philosophy

If the same UI appears repeatedly, consider a Blade component.

Examples:

``` text
product-card
product-price
rating
button
navbar
footer
status-badge
```

Instead of duplicating large blocks of HTML.

For example:

``` blade
<x-product-card :product="$product" />
```

The component should receive the data it needs explicitly.

------------------------------------------------------------------------

# 25. Frontend Data Principle

A view should have a clear contract.

Before designing or editing a page, answer:

``` text
1. What page is this?
2. Which controller renders it?
3. Which variables does the controller provide?
4. Which relationships are loaded?
5. Which data is optional/null?
6. What should happen when there is no data?
7. Which actions link to routes/API endpoints?
8. Which actions require authentication?
```

This prevents UI implementation from becoming disconnected from the
backend.

------------------------------------------------------------------------

# 26. Empty / Loading / Error States

Every data-driven view should consider at least:

``` text
Normal state
Empty state
Unavailable state
Error state
```

For example, Best Sellers:

``` blade
@if ($bestSellers->isEmpty())
    <p>No best-selling products available yet.</p>
@else
    ...
@endif
```

Do not assume the database always contains data.

------------------------------------------------------------------------

# 27. Null-Safety

Relationships can be nullable or missing.

For example:

``` php
$product->description
```

may be null.

A category relationship should normally exist because of the foreign
key, but views should still avoid crashing when optional data is
missing.

Use appropriate Laravel/PHP mechanisms when needed:

``` php
optional($product->category)->name
```

or null-safe access:

``` php
$product->category?->name
```

Do not add excessive defensive code where the database constraint
already guarantees the relationship.

------------------------------------------------------------------------

# 28. Pricing Display

Database:

``` text
18000
22000
25000
```

UI:

``` text
Rp 18.000
Rp 22.000
Rp 25.000
```

Use:

``` php
number_format($product->price, 0, ',', '.')
```

Do not store:

``` text
"Rp 18.000"
```

inside the numeric database price field.

Database values should remain numeric.

------------------------------------------------------------------------

# 29. Route Naming and Navigation

Prefer named routes for application navigation.

Example:

``` php
Route::get('/', [HomeController::class, 'index'])
    ->name('home');
```

Then:

``` blade
<a href="{{ route('home') }}">Home</a>
```

rather than hardcoding URLs everywhere.

When route names exist, use them consistently.

------------------------------------------------------------------------

# 30. Current Route State

The current route list contains:

``` text
GET /
```

currently mapped to:

``` text
routes/web.php:5
```

The intended homepage architecture is to use a dedicated
`HomeController@index`.

API routes currently cover:

``` text
authentication
profile
products
categories
transactions
payments
reviews
admin management
```

When changing routes, verify with:

``` bash
php artisan route:list
```

------------------------------------------------------------------------

# 31. Database Seeder Philosophy

Seeders should create realistic relational data in dependency order.

Recommended order:

``` text
1. users
2. categories
3. products
4. transactions
5. transaction_details
6. payments
7. reviews
```

Because:

``` text
transactions → users
products → categories
transaction_details → transactions + products
payments → transactions
reviews → users + products
```

Foreign keys must reference records that already exist.

------------------------------------------------------------------------

# 32. Testing the Backend

Useful commands:

``` bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
php artisan route:list
php artisan storage:link
```

For API testing, Postman can be used to test:

``` text
GET
POST
PUT
DELETE
```

For every endpoint, verify:

``` text
HTTP status
response JSON
validation
authentication
authorization
database effect
edge cases
```

------------------------------------------------------------------------

# 33. Development Workflow

When adding a new feature:

``` text
1. Understand the user flow
2. Check database structure
3. Check existing relationships
4. Decide whether it is Web, API, or both
5. Create/update migration if database changes
6. Update model relationships/casts
7. Implement validation
8. Implement authorization
9. Implement controller
10. Register route
11. Test API/route
12. Prepare controller data for the view
13. Build Blade view
14. Test empty/error states
15. Test the complete user flow
```

Do not start by writing HTML before understanding the data contract.

------------------------------------------------------------------------

# 34. Change Management Rules

Before changing an existing implementation:

-   Check the current route.
-   Check the current controller.
-   Check the relevant model relationship.
-   Check the database column names.
-   Check whether the value is current or historical.
-   Check whether the endpoint is customer or admin.
-   Check whether authentication/authorization is required.
-   Preserve existing behavior unless the requested change explicitly
    replaces it.

When uncertain, prefer the existing project structure over inventing a
new architecture.

------------------------------------------------------------------------

# 35. Important Naming Conventions

Use Laravel/PHP naming conventions:

``` text
Models:
User
Product
TransactionDetail

Tables:
users
products
transaction_details

Variables:
$bestSellers
$product
$transaction

Methods:
index()
show()
store()
update()
destroy()
```

Relationship methods should describe the related entity:

``` text
category()
products()
transactionDetails()
transaction()
payment()
reviews()
```

------------------------------------------------------------------------

# 36. What the AI Should Assume

Unless the user explicitly changes the architecture, assume:

``` text
Framework: Laravel 13
ORM: Eloquent
Template engine: Blade
Database architecture: relational
Authentication/API: Laravel-compatible authentication/Sanctum architecture
Payment provider: Midtrans
Frontend page style: simple modern Tikona coffee brand
Primary brand color: #E29C23
Font direction: Urbanist
```

Do not invent additional database tables, columns, relationships, or API
endpoints without explaining why they are necessary.

------------------------------------------------------------------------

# 37. What the AI Should Ask Before Making a Major Change

If a requested implementation conflicts with the existing architecture,
clarify the conflict before making destructive changes.

Examples:

``` text
- Changing order status values
- Changing enum roles
- Replacing transaction_details with a different cart schema
- Moving product images to a different storage strategy
- Changing Midtrans integration architecture
- Changing customer/admin authorization model
```

For small implementation details, use the existing conventions without
unnecessary clarification.

------------------------------------------------------------------------

# 38. Golden Rule for Tikona

Always maintain this mental model:

``` text
DATABASE
    ↓
MODEL + RELATIONSHIPS
    ↓
CONTROLLER
    ↓
VIEW / API
    ↓
USER
```

For a Blade page:

``` text
Database
    ↓
Eloquent
    ↓
Controller prepares data
    ↓
Blade renders UI
```

For an API:

``` text
Client
    ↓
API Route
    ↓
Controller
    ↓
Validation + Authorization
    ↓
Eloquent
    ↓
JSON
```

For an order:

``` text
User
    ↓
Transaction
    ↓
TransactionDetails
    ↓
Payment
    ↓
Midtrans
```

For product sales:

``` text
Product
    ↓
TransactionDetails
    ↓
Transaction
    ↓
quantity
    ↓
Total units sold
    ↓
Best Seller
```

------------------------------------------------------------------------

# 39. Final Principle

**Views should consume well-defined data, not discover or calculate the
application's business logic themselves.**

When creating a new Tikona view, first define:

``` text
View
↓
Controller
↓
Data required
↓
Model relationships
↓
Database source
↓
Routes/actions
```

Once that contract is clear, the UI can be built independently and
consistently.

The goal is for the backend and frontend to share the same mental model
of the Tikona system.
