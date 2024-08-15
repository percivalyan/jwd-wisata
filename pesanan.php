<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'jwd_wisata';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus data jika ada permintaan DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM booking WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pesanan.php';
                    }
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus data: " . $stmt->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
    $stmt->close();
}

// Ambil data booking
$sql = "SELECT * FROM booking";
$result = $conn->query($sql);

$conn->close();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Header -->
    <div class="header" data-aos="fade-down">
        <h1>Wisata Pandu Tour Jateng - Jogja</h1>
    </div>

    <!-- Hero Section dengan Grid Gambar -->
    <div class="hero" data-aos="zoom-in">
        <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Candi Borobudur" class="large">
        <img src="https://images.unsplash.com/photo-1628488321763-eb2f79b7f3b5?q=80&w=1997&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Candi Prambanan">
        <img src="https://images.unsplash.com/photo-1687677347190-58c4ebf93bf6?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Malioboro">
        <img src="https://images.unsplash.com/photo-1617421358309-d3c9809ffda7?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Pantai Parangtritis">
        <img src="https://images.unsplash.com/photo-1637304497628-c69e8684c11a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Dieng Plateau">
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Pandu Tour</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pesan.php">Daftar Paket Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pesanan.php">Modifikasi Pesanan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="py-5"></div>

    <div class="container mt-5">
        <h2>Data Pesanan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pemesan</th>
                    <th>Nomor HP</th>
                    <th>Tanggal Pesan</th>
                    <th>Waktu Perjalanan</th>
                    <th>Pelayanan Paket</th>
                    <th>Jumlah Peserta</th>
                    <th>Harga Paket</th>
                    <th>Jumlah Tagihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nama_pesanan']; ?></td>
                        <td><?= $row['nomor_hp']; ?></td>
                        <td><?= $row['tanggal_pesan']; ?></td>
                        <td><?= $row['waktu_perjalanan']; ?></td>
                        <td><?= $row['pelayanan_paket']; ?></td>
                        <td><?= $row['jumlah_peserta']; ?></td>
                        <td><?= number_format($row['harga_paket'], 2, ',', '.'); ?></td>
                        <td><?= number_format($row['jumlah_tagihan'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="edit_pesanan.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button id="printButton" class="btn btn-primary">Print Data</button>
    </div>

    <div class="py-5"></div>

    <!-- Footer Section -->
    <footer class="footer bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>About Us</h5>
                    <p>We are Pandu Tour, dedicated to offering the best travel experiences in Central Java and Yogyakarta. Explore our curated tour packages and enjoy the rich cultural heritage of Indonesia.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Home</a></li>
                        <li><a href="#" class="text-white">Tour Packages</a></li>
                        <li><a href="#" class="text-white">Contact Us</a></li>
                        <li><a href="#" class="text-white">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-phone-alt"></i> +62 123 456 789</p>
                    <p><i class="fas fa-envelope"></i> info@pandutour.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Wisata No. 1, Jogjakarta, Indonesia</p>
                </div>
            </div>
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Pandu Tour. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.5.19/jspdf.plugin.autotable.min.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });

        document.getElementById('printButton').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.text('Data Pesanan', 10, 10);a

            const table = document.querySelector('table');
            const rows = Array.from(table.querySelectorAll('tr')).map(tr => {
                return Array.from(tr.querySelectorAll('th, td')).map(td => td.innerText);
            });

            doc.autoTable({
                head: [rows[0]],
                body: rows.slice(1),
                margin: {
                    top: 20
                },
            });

            doc.save('data_pesanan.pdf');
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button click
            document.querySelectorAll('.btn-danger').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = this.href;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data ini akan dihapus secara permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>