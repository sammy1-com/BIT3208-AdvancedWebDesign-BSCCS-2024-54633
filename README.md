# PitStop Parts — Week 3: JavaScript and PHP Basics

## What was done this week
- Added JavaScript form validation to Login (client-side before PHP)
- Built password strength checker on Register using DOM events
- Created AJAX dynamic model dropdown using Fetch API
- Built hero image slideshow with JS
- Added search tab switching (By Vehicle / By Part)
- Added PHP server-side validation on Login and Register
- Created `includes/functions.php` with helper functions
- Set up `ajax/get-models.php` to return JSON data

## What is new vs Week 2
| Feature | Week 2 | Week 3 |
|---|---|---|
| Login validation | None | JS + PHP validation |
| Register validation | None | JS + PHP + password strength |
| Hero slideshow | Static image | JS auto-cycling slideshow |
| Model dropdown | Static | Dynamic via Fetch API/AJAX |
| PHP helpers | None | `functions.php` with redirect, format_price |
| DB | Not used | Connected, not yet queried for content |

## Files added this week
| File | Purpose |
|---|---|
| `includes/functions.php` | PHP helper functions |
| `includes/db.php` | mysqli database connection |
| `ajax/get-models.php` | AJAX endpoint — returns models JSON |
| `assets/js/main.js` | Hero slider, tabs, Fetch API, scroll reveal |

## Technologies used
- JavaScript (DOM, Events, Fetch API, IntersectionObserver)
- PHP 8 (validation, POST handling, functions)
- MySQL (connected but not yet queried for page content)
