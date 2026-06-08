
FILE STRUCTURE
pitstop/
  index.php           Homepage
  shop.php            Product catalogue
  product.php         Product detail
  cart.php            Shopping cart
  checkout.php        Checkout
  login.php           Login
  register.php        Register
  logout.php          Logout
  includes/
    db.php            Database connection
    functions.php     Helper functions
    header.php        Site header/nav
    footer.php        Site footer
  assets/
    css/style.css     All styles
    js/main.js        All JavaScript
    images/           Hero and product images
  uploads/parts/      Admin-uploaded product images
  admin/
    index.php         Dashboard
    products.php      List all products (Read)
    add-product.php   Add new product (Create)
    edit-product.php  Edit product (Update)
    delete-product.php Delete product (Delete)
    orders.php        View and update orders
  ajax/
    get-models.php    Returns models for a make
  sql/
    pitstop.sql       Full database schema and seed data
