<?php
$email = $_POST['email'] ?? '';
$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$password = $_POST['password'] ?? '';
$confirmpassword = $_POST['confirmpassword'] ?? '';
$number = $_POST['number'] ?? '';

$conn = new mysqli('localhost:8080', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Check if email or number already exists
    $checkStmt = $conn->prepare("SELECT * FROM registration WHERE email = ? OR number = ?");
    $checkStmt->bind_param("ss", $email, $number);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "You have already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO registration(email, firstname, lastname, password, confirmpassword, number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $email, $firstname, $lastname, $password, $confirmpassword, $number);
        $stmt->execute();

        // Check if the registration was successful
        if ($stmt->affected_rows > 0) {
            // Registration successful, send email to the user
            require 'PHPMailer/PHPMailerAutoload.php';
            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yekulabhargav789@gmail.com'; // Your Gmail address
            $mail->Password = 'iakvbshhfvvctksj'; // Your Gmail password     //iakv bshh fvvc tksj
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yekulabhargav789@gmail.com', 'Kalyan'); // Your Name and your email
            $mail->addAddress($email, $firstname); // User's email and name
            $mail->isHTML(true);

            $mail->Subject = 'Thank You for Registration';
            $mail->Body    = 'Hello ' . $firstname . ',<br><br>Thank you for registering with us. We appreciate your interest.<br><br>Regards,<br>Your Host/Admin';

            if (!$mail->send()) {
                echo 'Message could not be sent.';
            } else {
                echo 'Account Created Successfully. An email has been sent to ' . $email . ' with further instructions.';
            }
        } else {
            echo "Failed to create account.";
        }
        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>