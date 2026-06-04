# PitStop Parts — Week 5: Database Components and CRUD Operations

## What was done this week
- Created full 7-table database schema in phpMyAdmin
- Built complete Admin CRUD panel (Create, Read, Update, Delete products)
- Completed checkout flow — form collects shipping info and saves order to DB
- Stock automatically decremented on checkout
- Admin can view and update order status (pending → processing → shipped → delivered)
- Product detail page (`product.php`) with "Add to Cart" and related products

## Final project feature summary
| Feature | Status |
|---|---|
| Dynamic homepage | ✅ Live from DB |
| Product catalogue with filters | ✅ Search, category, condition |
| Product detail page | ✅ With related products |
| Session cart (add, update, remove) | ✅ |
| Checkout with shipping form | ✅ Saves to orders table |
| User registration | ✅ Hashed passwords |
| Login / Logout | ✅ Session-based |
| Admin dashboard | ✅ Stats overview |
| Admin product management | ✅ Full CRUD |
| Admin order management | ✅ Status updates |
| AJAX model dropdown | ✅ Fetch API |
| Mobile responsive | ✅ Bootstrap 5 grid |

## Database setup
1. Open `http://localhost/phpmyadmin`
2. Click **Import** → Select `sql/pitstop.sql` → **Go**

## Admin login
- **Email:** `admin@pitstopparts.co.ke`
- **Password:** `password`

## File structure
```
Week5/
├── index.php          Homepage (dynamic)
├── shop.php           Product catalogue
├── product.php        Product detail
├── cart.php           Shopping cart
├── checkout.php       Checkout + order placement
├── login.php          Login
├── register.php       Register
├── logout.php         Logout
├── includes/
│   ├── db.php         Database connection
│   ├── functions.php  All helper functions
│   ├── header.php     Nav (session-aware)
│   └── footer.php     Footer + JS
├── admin/
│   ├── index.php      Dashboard
│   ├── products.php   List products (Read)
│   ├── add-product.php    Add product (Create)
│   ├── edit-product.php   Edit product (Update)
│   ├── delete-product.php Delete product (Delete)
│   └── orders.php     Order management
├── ajax/
│   └── get-models.php AJAX model dropdown
├── assets/
│   ├── css/style.css  All styles
│   └── js/main.js     Hero slider, tabs, AJAX
└── sql/
    └── pitstop.sql    Full DB schema + seed data
```
