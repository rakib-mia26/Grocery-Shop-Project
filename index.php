<?php
session_start();
include('db_initialize.php');
include('db.php');


// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header with Shop Name and Login Button -->
    <header>
        <div class="header-container">
            <h1>Online Grocery Shop</h1>
           
            <div class="login-container">
                <?php if ($isLoggedIn): ?>
                    <a href="cart.php">Cart</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Product List Section -->
    <div class="product-list">
        <h2>Available Products</h2>
        <div class="products">
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    <?php if ($isLoggedIn): ?>
                        <a href="product_details.php?id=<?php echo $product['id']; ?>">Product Details</a>
                        <a href="cart.php?add_to_cart=add&product_id=<?php echo $product['id']; ?>" class="add-to-cart-btn">Add to Cart</a>
                    <?php else: ?>
                        <p><a href="login.php">Login to Add to Cart</a></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
