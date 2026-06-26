# PitStop Parts — Week 8: Mobile Responsiveness & Compatibility

## What was done this week
- Added hamburger nav (collapses on screens under 768px)
- Product grid drops to 2 columns on mobile
- Shop filter bar stacks vertically on small screens
- Cart table becomes card-based on mobile (no horizontal scroll)
- Admin tables wrapped in overflow-x:auto for horizontal scroll on mobile
- Order cards (my-orders.php) fully responsive flex layout
- Role badges styled and consistent across all pages
- Auth pages (login/register) verified responsive
- Admin dashboard sidebar hides on mobile, board stacks to single column

## Files updated this week
| File | Change |
|---|---|
| `assets/css/style.css` | Appended mobile breakpoints, hamburger styles, role badges, order cards |
| `includes/header.php` | Hamburger button + JS toggle already included from Week 6 |

## Breakpoints used
| Breakpoint | Change |
|---|---|
| max-width: 900px | Admin sidebar hides, dashboard board stacks |
| max-width: 768px | Hamburger nav activates |
| max-width: 600px | Product grid 2-col, cart table card view |
