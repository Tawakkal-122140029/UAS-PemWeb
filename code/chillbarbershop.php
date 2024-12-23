<?php
session_start();

// Koneksi ke Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chillbarbershop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logika Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: chillbarbershop.php');
    exit();
}

// Menampilkan pesan flash jika ada
if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . htmlspecialchars($_SESSION['flash_message']) . "');</script>";
    unset($_SESSION['flash_message']); // Hapus pesan setelah ditampilkan
}

// Logika hapus booking
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $id = $_SESSION['user_fname']; // Nama depan pengguna yang sedang login

    $booking = new Booking($conn);
    $booking->deleteBooking($delete_id, $id);

    header('Location: chillbarbershop.php');
    exit();
}

// Masukkan file class Booking
require_once 'Booking.php';

// Logika tambah booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = $_POST['name'];
    $capster = $_POST['capster'];
    $lokasi = $_POST['location'];
    $waktu_reservasi = $_POST['time'];

    // Validasi nama pelanggan (hanya huruf dan spasi)
    $regex = "/^[a-zA-Z\s]*$/";
    if (!preg_match($regex, $nama_pelanggan)) {
        $_SESSION['flash_message'] = 'Nama pelanggan tidak valid. Booking tidak berhasil.';
        header('Location: chillbarbershop.php');
        exit();
    }

    $booking = new Booking($conn);
    $booking->addBooking($nama_pelanggan, $capster, $lokasi, $waktu_reservasi);
}

// Ambil data booking dari database
$bookings = $conn->query("SELECT * FROM bookings");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChillBarbershop | Best Barbershop in Town</title>
    <link rel="stylesheet" href="style-home.css">
</head>
<body>
    <!-- Bagian Navbar -->
    <section id="navbar">
        <div class="nav">
            <img src="../img/logo.jpg" alt="logo.jpg" class="icon">
            <nav>
                <ul>
                    <li><a href="chillbarbershop.php">Home</a></li>
                    <li><a href="#booking">Book</a></li>
                    <li><a href="#cookie">Cookie</a></li>
                    <?php if (isset($_SESSION['user_fname'])): ?>
                        <li style="font-size: 20px;"> <?= htmlspecialchars($_SESSION['user_fname']); ?> </li>
                        <li><a href="?logout=true">Logout</a></li>
                    <?php else: ?>
                        <li><a href="signup.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Bagian Services -->
    <section id="services">
        <div class="main-container">
            <h1 class="pre-title">Our Services</h1>
            <h3 class="section-title">Silahkan pilih servis sesuai dengan yang diinginkan</h3>
            <div class="services-grid">
                <!-- Layanan potong rambut -->
                <div class="service">
                    <div class="service-icon">
                        <img src="../img/haircut.png" alt="haircut.png">
                    </div>
                    <div class="service-info">
                        <div class="service-title">
                            <h4>Haircut</h4>
                        </div>
                    </div>
                </div>

                <!-- Layanan gaya rambut -->
                <div class="service">
                    <div class="service-icon">
                        <img src="../img/hairstyle.png" alt="hairstyle.png">
                    </div>
                    <div class="service-info">
                        <div class="service-title">
                            <h4>Hairstyle</h4>
                        </div>
                    </div>
                </div>

                <!-- Layanan cuci rambut -->
                <div class="service">
                    <div class="service-icon">
                        <img src="../img/hairwash.png" alt="hairwash.png">
                    </div>
                    <div class="service-info">
                        <div class="service-title">
                            <h4>Hairwash</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bagian Booking -->
    <section id="booking">
        <div class="main-container">
            <h1 class="pre-title">Booking Online</h1>
            <h3 class="section-title">Nikmati kemudahan memesan slot cukur rambut kapan saja tanpa perlu menunggu lama</h3>
            
            <!-- Tabel daftar booking -->
            <div class="list-book">
                <h1>List Book</h1>
                <table class="table-book">
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Capster</th>
                            <th>Lokasi</th>
                            <th>Waktu Reservasi</th>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                                <td><?= htmlspecialchars($row['capster']); ?></td>
                                <td><?= htmlspecialchars($row['lokasi']); ?></td>
                                <td><?= htmlspecialchars($row['waktu_reservasi']); ?></td>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <td>
                                        <form method="POST" action="delete_booking.php">
                                            <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                                            <button type="submit" style="color: #fff; cursor: pointer;">Delete</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Form untuk membuat booking baru -->
            <div class="book">
                <button id="openForm">Buat Booking</button>
            </div>

            <div id="bookingForm" class="form-container" style="display: none;">
                <form method="POST" action="">
                    <h3>Isi Data Terlebih Dahulu!</h3>
                    <label for="name">Nama Pelanggan:</label>
                    <input type="text" id="name" name="name" required>
                    <small id="nameError" style="color: red; display: none;">Nama hanya boleh berisi huruf dan spasi.</small>

                    <label for="capster">Pilih Capster:</label>
                    <select id="capster" name="capster" required>
                        <option value="Budi">Budi</option>
                        <option value="Andi">Andi</option>
                        <option value="Citra">Citra</option>
                    </select>

                    <label for="location">Lokasi:</label>
                    <input type="text" id="location" name="location" required>

                    <label for="time">Waktu Reservasi:</label>
                    <input type="datetime-local" id="time" name="time" required>

                    <button type="submit">Submit</button>
                    <button type="button" id="closeForm">Cancel</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Bagian Cookie Management -->
    <section id="cookie">
        <div class="main-container">
            <h1 class="pre-title">Management Cookies</h1>
            <h3 class="section-title">Let's Set, Get and Delete Cookies!</h3>
            <div class="cookie-area">
                <input type="text" name="name" id="name-cookie" placeholder="Enter cookie value">
                <button onclick="setCookie()">Set Cookie</button>
                <button onclick="getCookie()">Get Cookie</button>
                <button onclick="deleteCookie()">Delete Cookie</button>
            </div>
            <div id="result"></div>
        </div>
    </section>

    <script>
        // Logika untuk form booking
        document.getElementById("openForm").addEventListener("click", function() {
            const bookingForm = document.getElementById("bookingForm");
            bookingForm.style.display = "block";
            bookingForm.classList.add("active");
            setTimeout(() => {
                bookingForm.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 100);
        });

        document.getElementById("closeForm").addEventListener("click", function() {
            const bookingForm = document.getElementById("bookingForm");
            bookingForm.classList.remove("active");
            setTimeout(() => {
                bookingForm.style.display = "none";
            }, 500);
        });

        // Validasi nama pelanggan saat input
        document.getElementById("name").addEventListener("input", function() {
            const nameField = this;
            const nameError = document.getElementById("nameError");
            const regex = /^[a-zA-Z\s]*$/; // Hanya huruf dan spasi

            if (!regex.test(nameField.value)) {
                nameError.style.display = "block";
                nameField.style.borderColor = "red"; // Indikator visual
            } else {
                nameError.style.display = "none";
                nameField.style.borderColor = ""; // Kembali ke normal
            }
        });

        // Fungsi manajemen cookie
        function setCookie() {
            const name = document.getElementById("name-cookie").value;
            fetch('cookie_manager.php?action=set&name=' + encodeURIComponent(name))
                .then(response => response.text())
                .then(data => document.getElementById("result").innerHTML = data);
        }

        function getCookie() {
            fetch('cookie_manager.php?action=get')
                .then(response => response.text())
                .then(data => document.getElementById("result").innerHTML = data);
        }

        function deleteCookie() {
            fetch('cookie_manager.php?action=delete')
                .then(response => response.text())
                .then(data => document.getElementById("result").innerHTML = data);
        }
    </script>
</body>
</html>