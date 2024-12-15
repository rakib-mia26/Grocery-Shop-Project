<?php
session_start();
include('db.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not admin
    exit();
}

// Handle product CRUD operations
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
    $conn->query($sql);
} elseif (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $sql = "UPDATE products SET name = '$name', price = '$price', image = '$image' WHERE id = $id";
    $conn->query($sql);
} elseif (isset($_POST['delete_product'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM products WHERE id = $id";
    $conn->query($sql);
}

// Handle customer removal
if (isset($_GET['remove_customer'])) {
    $customer_id = $_GET['remove_customer'];

    // Remove customer from users table
    $sql = "DELETE FROM users WHERE id = $customer_id";
    $conn->query($sql);

    // Also remove the customer's session
    $sql = "DELETE FROM sessions WHERE user_id = $customer_id";
    $conn->query($sql);
}
// Handle session removal
if (isset($_GET['remove_session'])) {
    $session_id = $_GET['remove_session'];

    // Remove the session from the sessions table
    $sql = "DELETE FROM sessions WHERE session_id = '$session_id'";
    $conn->query($sql);
}

// Fetch all active sessions
$sessions = $conn->query("SELECT * FROM sessions");

// Fetch all products
$products = $conn->query("SELECT * FROM products");

// Fetch all customers
$customers = $conn->query("SELECT * FROM users WHERE role = 'customer'");

// Fetch all active sessions
$sessions = $conn->query("SELECT * FROM sessions");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <a href="logout.php">Logout</a>
    </header>

    <div class="admin-dashboard">
        <!-- Product Management Section -->
        <section class="product-management">
            <h2>Product Management</h2>
            <form action="admin.php" method="POST">
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="number" name="price" placeholder="Product Price" required>
                <input type="text" name="image" placeholder="Image Filename" required>
                <button type="submit" name="add_product">Add Product</button>
            </form>

            <h3>Existing Products</h3>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><img src="img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image"></td>
                        <td>
                            <form action="admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                                <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
                                <input type="text" name="image" value="<?php echo $product['image']; ?>" required>
                                <button type="submit" name="edit_product">Edit</button>
                            </form>
                            <a href="admin.php?delete_product=<?php echo $product['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <!-- Customer Management Section -->
        <section class="customer-management">
            <h2>Customer Management</h2>
            <h3>List of Customers</h3>
            <table>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($customer = $customers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $customer['name']; ?></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td>
                            <a href="admin.php?remove_customer=<?php echo $customer['id']; ?>">Remove Customer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <!-- Active Sessions Section -->
        <section class="sessions">
            <h2>Active Sessions</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>User Email</th>
                    <th>Session ID</th>
                    <th>Last Activity</th>
                    <th>Remove Session</th>
                </tr>
                <?php while ($session = $sessions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $session['user_id']; ?></td>
                        <td><?php echo $session['email']; ?></td>
                        <td><?php echo $session['session_id']; ?></td>
                        <td><?php echo $session['last_activity']; ?></td>
                        <td>
                            <a href="admin.php?remove_session=<?php echo $session['session_id']; ?>" onclick="return confirm('Are you sure you want to remove this session?')">Remove Session</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </div>
</body>
</html>

<?php $conn->close(); ?>
