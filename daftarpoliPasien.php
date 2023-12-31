<?php
include_once("koneksi.php");

// Check if the user is logged in and 'id' is set
if (!isset($_SESSION['nama']) || !isset($_SESSION['id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

$no_rm = '';
$nama = $_SESSION['nama'];

$query = "SELECT no_rm FROM pasien WHERE nama = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $nama);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query error: " . $mysqli->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $no_rm = $row['no_rm'];
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['daftar'])) {
    $newid_jadwal = $_POST['jadwal'];
    $newkeluhan = $_POST['keluhan'];

    // Check if 'id' is set in the $_SESSION array
    if (isset($_SESSION['id'])) {
        $newid_pasien = $_SESSION['id'];
    } else {
        // Handle the case where 'id' is not set
        // You can redirect the user or show an error message
        echo "Error: User ID not set in session.";
        exit();
    }

    // Fetch the maximum existing queue number for the selected polyclinic
    $queryMaxQueue = "SELECT MAX(no_antrian) as max_queue FROM daftar_poli WHERE id_jadwal = ?";
    $stmtMaxQueue = $mysqli->prepare($queryMaxQueue);
    $stmtMaxQueue->bind_param("s", $newid_jadwal);
    $stmtMaxQueue->execute();
    $resultMaxQueue = $stmtMaxQueue->get_result();

    if ($resultMaxQueue && $resultMaxQueue->num_rows > 0) {
        $rowMaxQueue = $resultMaxQueue->fetch_assoc();
        $newno_antrian = $rowMaxQueue['max_queue'] + 1;
    } else {
        // If no existing queue, start from 1
        $newno_antrian = 1;
    }

    $newstatus_periksa = 0;

    $insertQuery = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian, status_periksa) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertQuery);
    $stmt->bind_param("sssss", $newid_pasien, $newid_jadwal, $newkeluhan, $newno_antrian, $newstatus_periksa);

    if ($stmt->execute()) {
        // Insert successful
    } else {
        $error = "Error: " . $mysqli->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head section remains unchanged -->
</head>

<body>
    <!-- Your existing HTML code goes here -->

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center" style="font-weight: bold; font-size: 32px;">Daftar Poli</div>
                    <div class="card-body">
                        <?php
                        // Check if the form is submitted and there is no error
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['daftar']) && empty($error)) {
                            echo '<div class="alert alert-success" role="alert">Pendaftaran berhasil dilakukan! Terima kasih atas pendaftarannya. No Antrian Anda: ' . $newno_antrian . '</div>';
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="no_rm">No Rekam Medis</label>
                                <input type="text" name="no_rm" class="form-control" required value="<?= $no_rm ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_poli">Pilih Poli</label>
                                <select name="nama_poli" class="form-control" required>
                                    <?php
                                    // Fetch and display polyclinics from the poli table
                                    $queryPoli = "SELECT nama_poli FROM poli";
                                    $resultPoli = $mysqli->query($queryPoli);

                                    if ($resultPoli && $resultPoli->num_rows > 0) {
                                        while ($rowPoli = $resultPoli->fetch_assoc()) {
                                            echo '<option value="' . $rowPoli['nama_poli'] . '">' . $rowPoli['nama_poli'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jadwal">Pilih Jadwal</label>
                                <select name="jadwal" class="form-control" required>
                                    <?php
                                    // Fetch and display available schedules from the jadwal_periksa table
                                    $queryJadwal = "SELECT * FROM jadwal_periksa";
                                    $resultJadwal = $mysqli->query($queryJadwal);

                                    if ($resultJadwal && $resultJadwal->num_rows > 0) {
                                        while ($rowJadwal = $resultJadwal->fetch_assoc()) {
                                            echo '<option value="' . $rowJadwal['id'] . '">' . $rowJadwal['hari'] . ' (' . $rowJadwal['jam_mulai'] . ' - ' . $rowJadwal['jam_selesai'] . ')' . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keluhan">Keluhan</label>
                                <input type="text" name="keluhan" class="form-control" required placeholder="Masukkan keluhan">
                            </div>
                            <div class="text-center">
                                <button type="submit" name="daftar" class="btn btn-primary btn-block">Daftar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your remaining HTML code goes here -->

    <!-- JavaScript libraries remain unchanged -->
</body>

</html>
