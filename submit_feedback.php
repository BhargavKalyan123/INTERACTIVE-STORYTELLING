<?php
$feedback = $_POST['feedback'] ?? '';
$conn = new mysqli('localhost:8080', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
        $stmt = $conn->prepare("INSERT INTO feedback(feedback) VALUES (?)");
        $stmt->bind_param("s", $feedback);
        $stmt->execute();
    }
    echo 'Thank You for your  Feedback';
    $stmt->close();
    $conn->close();
?>