<?php
session_start();

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chillbarbershop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (strlen($fname) < 3 || strlen($lname) < 3) {
        echo "<script>alert('First and Last Name must be at least 3 characters long.');</script>";
    } else {
        // Periksa apakah email sudah terdaftar
        $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            echo "<script>alert('Email already exists. Please use another email.');</script>";
        } else {
            $sql = "INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $fname, $lname, $email, $password);

            if ($stmt->execute()) {
                $new_user_id = $conn->insert_id;
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_fname'] = $fname;

                header('Location: chillbarbershop.php');
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $check_email->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join With Us</title>
    <link rel="stylesheet" href="style-signup.css">
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form method="POST" action="">
                    <h2>Join With Us!</h2>
                    <div class="inputbox">
                        <input type="text" name="fname" id="fname" required>
                        <label for="fname">First Name*</label>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="lname" id="lname" required>
                        <label for="lname">Last Name*</label>
                    </div>
                    <div class="inputbox">
                        <input type="email" name="email" id="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="inputbox">
                        <input type="password" name="password" id="password" required>
                        <label for="password">Password</label>
                    </div>
                    <button>Sign Up</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
