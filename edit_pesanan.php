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

$id = $_GET['id'] ?? 0;
$error = '';
$success = false;

// Ambil data pesanan untuk di-edit
$sql = "SELECT * FROM booking WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Proses form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pesanan = $_POST['nama_pesanan'];
    $nomor_hp = $_POST['nomor_hp'];
    $tanggal_pesan = $_POST['tanggal_pesan'];
    $waktu_perjalanan = $_POST['waktu_perjalanan'];
    $pelayanan_paket = isset($_POST['pelayanan_paket']) ? implode(", ", $_POST['pelayanan_paket']) : '';
    $jumlah_peserta = $_POST['jumlah_peserta'];

    // Validasi form
    if (empty($nama_pesanan) || empty($nomor_hp) || empty($tanggal_pesan) || empty($waktu_perjalanan) || empty($jumlah_peserta) || empty($pelayanan_paket)) {
        $error = "Semua field harus diisi!";
    } else {
        // Perhitungan harga paket
        $harga_paket = 0;
        if (in_array("Penginapan", $_POST['pelayanan_paket'])) {
            $harga_paket += 1000000;
        }
        if (in_array("Transportasi", $_POST['pelayanan_paket'])) {
            $harga_paket += 1200000;
        }
        if (in_array("Makanan", $_POST['pelayanan_paket'])) {
            $harga_paket += 500000;
        }

        // Perhitungan jumlah tagihan
        $jumlah_tagihan = $waktu_perjalanan * $jumlah_peserta * $harga_paket;

        // Update data pesanan
        $stmt = $conn->prepare("UPDATE booking SET nama_pesanan = ?, nomor_hp = ?, tanggal_pesan = ?, waktu_perjalanan = ?, pelayanan_paket = ?, jumlah_peserta = ?, harga_paket = ?, jumlah_tagihan = ? WHERE id = ?");
        $stmt->bind_param("sssissddi", $nama_pesanan, $nomor_hp, $tanggal_pesan, $waktu_perjalanan, $pelayanan_paket, $jumlah_peserta, $harga_paket, $jumlah_tagihan, $id);

        if ($stmt->execute()) {
            $success = true;
            // Redirect setelah berhasil
            header("Location: pesanan.php?success=1");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 800px;
        }
    </style>
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
        <h2>Edit Pesanan</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data pesanan berhasil diperbarui.',
                    icon: 'success'
                }).then(function() {
                    window.location.href = 'pesanan.php';
                });
            </script>
        <?php endif; ?>

        <form id="editForm" method="post" action="">
            <div class="form-group">
                <label for="nama_pesanan">Nama Pemesan</label>
                <input type="text" class="form-control" id="nama_pesanan" name="nama_pesanan" value="<?= htmlspecialchars($data['nama_pesanan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nomor_hp">Nomor HP/Telp</label>
                <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" value="<?= htmlspecialchars($data['nomor_hp']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_pesan">Tanggal Pesan</label>
                <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?= htmlspecialchars($data['tanggal_pesan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="waktu_perjalanan">Waktu Pelaksanaan Perjalanan (Hari)</label>
                <input type="number" class="form-control" id="waktu_perjalanan" name="waktu_perjalanan" value="<?= htmlspecialchars($data['waktu_perjalanan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="pelayanan_paket">Pelayanan Paket Perjalanan</label><br>
                <input type="checkbox" id="penginapan" name="pelayanan_paket[]" value="Penginapan" <?= strpos($data['pelayanan_paket'], 'Penginapan') !== false ? 'checked' : ''; ?>>
                <label for="penginapan">Penginapan (Rp 1,000,000)</label><br>
                <input type="checkbox" id="transportasi" name="pelayanan_paket[]" value="Transportasi" <?= strpos($data['pelayanan_paket'], 'Transportasi') !== false ? 'checked' : ''; ?>>
                <label for="transportasi">Transportasi (Rp 1,200,000)</label><br>
                <input type="checkbox" id="makanan" name="pelayanan_paket[]" value="Makanan" <?= strpos($data['pelayanan_paket'], 'Makanan') !== false ? 'checked' : ''; ?>>
                <label for="makanan">Makanan (Rp 500,000)</label><br>
            </div>
            <div class="form-group">
                <label for="jumlah_peserta">Jumlah Peserta</label>
                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" value="<?= htmlspecialchars($data['jumlah_peserta']); ?>" required>
            </div>
            <div class="form-group">
                <label for="harga_paket">Harga Paket Perjalanan (Rp)</label>
                <input type="text" class="form-control" id="harga_paket" name="harga_paket" readonly>
            </div>
            <div class="form-group">
                <label for="jumlah_tagihan">Jumlah Tagihan (Rp)</label>
                <input type="text" class="form-control" id="jumlah_tagihan" name="jumlah_tagihan" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
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

    <script src="https://cdn.jsdelivr.net/npm/jsPDF@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsPDF-autotable@2.5.19/dist/jspdf.plugin.autotable.min.js"></script>
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
    </script>
    <script>
        $(document).ready(function() {
            // Function to calculate the package price and total amount
            function calculate() {
                var hargaPaket = 0;
                if ($('#penginapan').is(':checked')) {
                    hargaPaket += 1000000;
                }
                if ($('#transportasi').is(':checked')) {
                    hargaPaket += 1200000;
                }
                if ($('#makanan').is(':checked')) {
                    hargaPaket += 500000;
                }

                $('#harga_paket').val(hargaPaket.toLocaleString('id-ID'));

                var waktuPerjalanan = parseInt($('#waktu_perjalanan').val()) || 0;
                var jumlahPeserta = parseInt($('#jumlah_peserta').val()) || 0;
                var jumlahTagihan = waktuPerjalanan * jumlahPeserta * hargaPaket;

                $('#jumlah_tagihan').val(jumlahTagihan.toLocaleString('id-ID'));
            }

            // Event listeners to recalculate values on input changes
            $('#waktu_perjalanan, #jumlah_peserta').on('input', calculate);
            $('input[name="pelayanan_paket[]"]').on('change', calculate);
            calculate(); // Calculate on page load

            // Handle form submission with SweetAlert confirmation
            $('#editForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin memperbarui data pesanan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    </script>
</body>

</html>