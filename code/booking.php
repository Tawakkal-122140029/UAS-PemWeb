<?php
class Booking {
    private $conn;

    // Constructor untuk menginisialisasi koneksi database
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Metode untuk menambahkan booking
    public function addBooking($nama_pelanggan, $capster, $lokasi, $waktu_reservasi) {
        $sql = "INSERT INTO bookings (nama_pelanggan, capster, lokasi, waktu_reservasi) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $nama_pelanggan, $capster, $lokasi, $waktu_reservasi);
        
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Booking berhasil ditambahkan!";
        } else {
            $_SESSION['flash_message'] = "Booking tidak berhasil. Silakan coba lagi.";
        }
    
        $stmt->close();
        header("Location: chillbarbershop.php");
        exit();
    }
    

    // Metode untuk menghapus booking
    public function deleteBooking($booking_id, $nama_pelanggan) {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE id = ? AND nama_pelanggan = ?");
        $stmt->bind_param("is", $booking_id, $nama_pelanggan);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $delete_stmt = $this->conn->prepare("DELETE FROM bookings WHERE id = ?");
            $delete_stmt->bind_param("i", $booking_id);
            
            if ($delete_stmt->execute()) {
                $_SESSION['flash_message'] = "Booking berhasil dihapus.";
            } else {
                $_SESSION['flash_message'] = "Gagal menghapus booking.";
            }
    
            $delete_stmt->close();
        } else {
            $_SESSION['flash_message'] = "Booking tidak ditemukan atau bukan milik Anda.";
        }
    
        $stmt->close();
        header("Location: chillbarbershop.php");
        exit();
    }
    
}
?>
