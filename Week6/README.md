# PitStop Parts — Week 6: Three-Tier Role System

## What was done this week
- Extended users table: added `manager` role to the ENUM
- Implemented three-tier role system: customer, manager, admin
- Added `is_manager_or_above()`, `require_manager_or_above()`, `get_role()` helper functions
- Manager logs in via same login form, redirects to admin dashboard
- Admin-only: `admin/users.php` — view all users, promote/demote roles
- Customer-facing: `my-orders.php` — view own order history
- All product/order admin pages now accessible to manager and above
- `admin/users.php` restricted to admin only

## Role definitions
| Role | Access |
|---|---|
| Customer | Browse, cart, checkout, my orders |
| Manager | Everything customer + products CRUD + order management + dashboard |
| Admin | Everything manager + user/role management |

## New files this week
| File | Purpose |
|---|---|
| `admin/users.php` | Admin-only user and role management |
| `my-orders.php` | Customer-facing order history |

## Files updated this week
| File | Change |
|---|---|
| `includes/functions.php` | Added `get_role()`, `is_manager_or_above()`, `require_manager_or_above()` |
| `login.php` | Manager now redirects to admin dashboard |
| `includes/header.php` | Role-aware nav, My Orders link, hamburger mobile nav |
| `admin/products.php` | Gate changed to `require_manager_or_above()` |
| `admin/add-product.php` | Gate changed to `require_manager_or_above()` |
| `admin/edit-product.php` | Gate changed to `require_manager_or_above()` |
| `admin/delete-product.php` | Gate changed to `require_manager_or_above()` |
| `admin/orders.php` | Gate changed to `require_manager_or_above()` |

## Database migration
```sql
ALTER TABLE users MODIFY COLUMN role ENUM('customer','manager','admin') DEFAULT 'customer';
```

## Test credentials
- Admin: `admin@pitstopparts.co.ke` / your password
- To test manager: promote any customer via `admin/users.php`
