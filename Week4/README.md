# PitStop Parts — Week 4: Server-Side Components and Backend Development

## What was done this week
- Implemented full session-based authentication (login, register, logout)
- Passwords hashed with `password_hash()` / verified with `password_verify()`
- User data stored in `$_SESSION` after successful login
- Header now shows "Hi, [Name] | Logout" when logged in, "Login | Register" when not
- Homepage and Shop now query real database via `functions.php`
- Cart built using PHP sessions (`$_SESSION['cart']`)
- All DB queries use `mysqli` prepared statements to prevent SQL injection

## What is new vs Week 3
| Feature | Week 3 | Week 4 |
|---|---|---|
| Login | PHP validation only | Real DB auth + sessions |
| Register | PHP validation only | Stores user to DB |
| Logout | None | Destroys session |
| Navigation | Static | Session-aware (login/logout) |
| Categories/Products | Hardcoded arrays | Live from DB |
| Cart | None | Session-based cart |

## Admin credentials (for testing)
- Email: `admin@pitstopparts.co.ke`
- Password: ``

## New files this week
| File | Purpose |
|---|---|
| `logout.php` | Clears session, redirects home |
| `cart.php` | Session-based cart management |
| `includes/header.php` | Updated — session-aware nav |
| `includes/functions.php` | Updated — real DB queries + auth helpers |
