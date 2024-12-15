<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle adding product to the cart
if (isset($_GET['add_to_cart']) && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $quantity = 1; // Default quantity for added products

    // Check if the product already exists in the user's cart
    $sql = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // If product already in cart, update the quantity
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
        $conn->query($sql);
    } else {
        // If product not in cart, insert into cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        $conn->query($sql);
    }
}

// Handle removing product from the cart
if (isset($_GET['remove_from_cart'])) {
    $cart_id = $_GET['remove_from_cart'];
    $sql = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
    $conn->query($sql);
}

// Handle updating the quantity of a product in the cart
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    $sql = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id";
    $conn->query($sql);
}

// Fetch all products in the user's cart
$sql = "SELECT cart.id, cart.quantity, products.name, products.price, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = $user_id";
$cart_items = $conn->query($sql);

// Calculate the total price
$total_price = 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <header>
        <h1>Your Cart</h1>
        <a href="index.php">Back to Shop</a>
    </header>

    <div class="cart-container">
        <?php if ($cart_items->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Remove</th>
                </tr>
                <?php while ($prod = $cart_items->fetch_assoc()): ?>
                    <?php $total_price =  $total_price + ($prod['price'] * $prod['quantity']) ?>
                    <tr>
                        <td>
                            <img src="img/<?php echo $prod['image']; ?>" alt="<?php echo $prod['name']; ?>" class="cart-image">
                            <?php echo $prod['name']; ?>
                        </td>
                        <td><?php echo $prod['price']; ?> BDT</td>
                        <td>
                            <form action="cart.php" method="POST" class="update-quantity-form">
                                <input type="hidden" name="cart_id" value="<?php echo $prod['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $prod['quantity']; ?>" min="1" required>
                                <button type="submit" name="update_quantity">Update</button>
                            </form>
                        </td>
                        <td>
                            <a href="cart.php?remove_from_cart=<?php echo $prod['id']; ?>">Remove</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="total-price">
                <h3>Total Price: <?php echo $total_price; ?> BDT</h3>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
