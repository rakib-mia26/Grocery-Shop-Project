<?php
$host = "localhost"; // Your MySQL server address
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "grocery_shop"; // The database name

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // Select the database
    mysqli_select_db($conn, $dbname);
} else {
    echo "Error creating database: " . $conn->error;
}

// Create the 'users' table if it doesn't exist
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer' NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    // echo "Table users created successfully\n";
} else {
    echo "Error creating users table: " . $conn->error;
}

// Create the 'products' table if it doesn't exist
$sql = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    details text,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(100) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    // echo "Table products created successfully\n";
} else {
    echo "Error creating products table: " . $conn->error;
}

// Insert sample products if the 'products' table is empty
$sql = "SELECT COUNT(*) AS total FROM products";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $sql = "
    INSERT INTO products (name, details, price, image) VALUES
    ('Atta','This is Atta have fun making ruti !', 50, 'Atta.jpeg'),
    ('Maida','This is Atta have fun making ruti !', 40, 'Maida.jpeg'),
    ('Suji','This is Atta have fun making ruti !', 45, 'Suji.jpeg'),
    ('Sugar','This is Atta have fun making ruti !', 60, 'Sugar.jpeg'),
    ('Salt','This is Atta have fun making ruti !', 20, 'Salt.jpeg'),
    ('Rice','This is Atta have fun making ruti !', 80, 'rice.jpg'),
    ('Oil','This is Atta have fun making ruti !', 120, 'Oil.jpeg'),
    ('Chili','This is Atta have fun making ruti !', 70, 'Chili.jpeg'),
    ('Coriander','This is Atta have fun making ruti !', 30, 'Coriander.jpeg')"
    ('Mango','This is Mango have fun making juice !', 50, 'Mango.jpeg')
    ;
    if ($conn->query($sql) === TRUE) {
        // echo "Sample products inserted\n";
    } else {
        echo "Error inserting products: " . $conn->error;
    }
}

// Create the 'cart' table if it doesn't exist
$sql = "
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    // echo "Table cart created successfully\n";
} else {
    echo "Error creating cart table: " . $conn->error;
}

// Create the 'sessions' table if it doesn't exist
$sql = "
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email VARCHAR(255),
    session_id VARCHAR(255) NOT NULL,
    ip_address VARCHAR(50),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    // echo "Table sessions created successfully\n";
} else {
    echo "Error creating sessions table: " . $conn->error;
}

// Close the connection
$conn->close();
?>
