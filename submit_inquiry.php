
<?php
$conn = new mysqli("localhost", "root", "", "sanctify_db");

if ($conn->connect_error) {
    die("Database connection failed.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($name === "" || $subject === "" || $message === "") {
        exit("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit("Enter a valid email address.");
    }

    $stmt = $conn->prepare(
        "INSERT INTO inquiries (name, email, subject, message)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    echo $stmt->execute()
        ? "Inquiry submitted successfully!"
        : "Unable to submit inquiry.";

    $stmt->close();
}

$conn->close();
?>


//in db
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);