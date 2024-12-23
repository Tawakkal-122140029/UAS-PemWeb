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

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You must be logged in to delete a booking.');
            window.location.href = 'chillbarbershop.php';
          </script>";
    exit();
}

// Periksa apakah booking_id diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $user_id = $_SESSION['user_id'];

    // Periksa apakah booking milik pengguna yang sedang login
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND nama_pelanggan = ?");
    $stmt->bind_param("is", $booking_id, $_SESSION['user_fname']); // Cocokkan dengan nama pelanggan
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hapus booking
        $delete_stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $delete_stmt->bind_param("i", $booking_id);

        if ($delete_stmt->execute()) {
            echo "<script>
                    alert('Booking berhasil dihapus.');
                    window.location.href = 'chillbarbershop.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus booking.');
                    window.location.href = 'chillbarbershop.php';
                  </script>";
        }
        $delete_stmt->close();
    } else {
        echo "<script>
                alert('Booking bukan milik Anda.');
                window.location.href = 'chillbarbershop.php';
              </script>";
    }
    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href = 'chillbarbershop.php';
          </script>";
}

$conn->close();
