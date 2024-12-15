<?php
include("db.php");
$id = $_GET['id'];
$result = $conn->query("select * from products where id = $id");
$product = $result->fetch_assoc()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px; line-height: 1.6;">
    <header style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #333;">Product Details</h1>
    </header>

    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto;">
        <img 
            src="img/<?php echo $product['image']?>" 
            alt="Product Image" 
            style="display: block; width: 100%; border-radius: 8px; margin-bottom: 20px;">

        <h2 style="color: #007BFF;"><?php echo $product['name'] ?></h2>
        <p style="color: #555; font-size: 16px;"><?php echo $product['details'] ?></p>

        <ul style="list-style: none; padding: 0; margin: 20px 0;">
            <li style="margin-bottom: 10px;">
                <strong>Price:</strong> <?php echo $product['price'] ?>
            </li>
            <li style="margin-bottom: 10px;">
                <strong>Availability:</strong> In Stock
            </li>
        </ul>
    <a href="cart.php?add_to_cart=add&product_id=<?php echo $product['id']; ?>">
        <button 
            style="background-color: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Add to Cart
        </button>
    </a>
    </div>

    <footer style="text-align: center; margin-top: 30px; color: #888; font-size: 14px;">
        &copy; 2024 Product Details. All rights reserved.
    </footer>
</body>
</html>
