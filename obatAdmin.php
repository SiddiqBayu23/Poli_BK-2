<?php
if (!isset($_SESSION)) {
  session_start();
}

// Include the database connection file (koneksi.php)
include_once("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_obat_modal'])) {
  $id = $_POST['id'];
  $newNamaObat = $_POST['new_nama_obat'];
  $newKemasan = $_POST['new_kemasan'];
  $newHarga = $_POST['new_harga'];

  // Update obat in the database using prepared statement
  $updateQuery = "UPDATE obat SET nama_obat=?, kemasan=?, harga=? WHERE id=?";
  $stmt = $mysqli->prepare($updateQuery);
  $stmt->bind_param("sssi", $newNamaObat, $newKemasan, $newHarga, $id);

  if ($stmt->execute()) {
    // Update successful
    header("Location: menuAdmin.php");
    exit();
  } else {
    // Update failed, handle error (you may redirect or display an error message)
    echo "Update failed: " . $stmt->error;
  }

  $stmt->close();
  
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_obat'])) {
  $newNamaObat = $_POST['add_nama_obat'];
  $newKemasan = $_POST['add_kemasan'];
  $newHarga = $_POST['add_harga'];

  // Insert new obat into the database using prepared statement
  $insertQuery = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES (?, ?, ?)";
  $stmt = $mysqli->prepare($insertQuery);
  $stmt->bind_param("sss", $newNamaObat, $newKemasan, $newHarga);

  if ($stmt->execute()) {
    // Insertion successful
    header("Location: menuAdmin.php");
    exit();
  } else {
    // Insertion failed, handle error (you may redirect or display an error message)
    echo "Insertion failed: " . $stmt->error;
  }

  $stmt->close();
}

// Menangani penghapusan obat dan catatan terkait di detail_periksa
if (isset($_POST['delete_obat'])) {
  $id = $_POST['id'];

  // Hapus catatan terkait di tabel detail_periksa terlebih dahulu
  $deleteDetailPeriksaQuery = "DELETE FROM detail_periksa WHERE id_obat=?";
  $stmtDetailPeriksa = $mysqli->prepare($deleteDetailPeriksaQuery);
  $stmtDetailPeriksa->bind_param("i", $id);

  // Jalankan penghapusan detail_periksa
  $stmtDetailPeriksa->execute();
  $stmtDetailPeriksa->close();

  // Lanjutkan dengan penghapusan obat
  $deleteObatQuery = "DELETE FROM obat WHERE id=?";
  $stmtObat = $mysqli->prepare($deleteObatQuery);
  $stmtObat->bind_param("i", $id);

  // Jalankan penghapusan obat
  if ($stmtObat->execute()) {
      // Penghapusan obat berhasil
      // Bersihkan output buffer
      ob_clean();

      // Redirect kembali ke halaman utama atau tampilkan pesan keberhasilan
      header("Location: menuAdmin.php");
      exit();
  } else {
      // Penghapusan obat gagal, tangani kesalahan
      echo "Penghapusan obat gagal: " . $stmtObat->error;
  }

  // Tutup prepared statement
  $stmtObat->close();
}





// Fetch data from the 'obat' table
$obatQuery = "SELECT * FROM obat";
$obatResult = $mysqli->query($obatQuery);

// Fetch the data as an associative array
$obatData = $obatResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Add your head section here -->

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">

  <!-- Add other necessary CSS links here -->
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addModal">Tambah Obat</button>

                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Obat</th>
                      <th>Kemasan</th>
                      <th>Harga</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($obatData as $obatRow) {
                      echo "<tr>";
                      echo "<td>" . $obatRow['id'] . "</td>";
                      echo "<td>" . $obatRow['nama_obat'] . "</td>";
                      echo "<td>" . $obatRow['kemasan'] . "</td>";
                      echo "<td>" . number_format($obatRow['harga'], 0, ',', '.') . " IDR</td>"; // Format harga as IDR
                      echo "<td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='id' value='" . $obatRow['id'] . "'>
                                                    <input type='hidden' name='new_nama_obat' value='" . $obatRow['nama_obat'] . "'>
                                                    <input type='hidden' name='new_kemasan' value='" . $obatRow['kemasan'] . "'>
                                                    <input type='hidden' name='new_harga' value='" . $obatRow['harga'] . "'>

                                                    <button type='button' name='update_obat' class='btn btn-warning btn-sm update-btn' data-toggle='modal' data-target='#updateModal' 
                                                    data-id='" . $obatRow['id'] . "' 
                                                    data-nama_obat='" . $obatRow['nama_obat'] . "' 
                                                    data-kemasan='" . $obatRow['kemasan'] . "' 
                                                    data-harga='" . $obatRow['harga'] . "'>Update</button>
                                                    
                                                    <form method='post' action=''>
                                                        <input type='hidden' name='id' value='" . $obatRow['id'] . "'>
                                                        <button type='submit' name='delete_obat' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</button>
                                                    </form>
                                                </form>
                                            </td>";
                      echo "</tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Perbarui Obat</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="menuAdmin.php">
            <!-- Replace with the actual update PHP file -->
            <input type="hidden" name="id" id="update_id">
            <div class="form-group">
              <label for="update_nama_obat">Nama Obat</label>
              <input type="text" class="form-control" id="update_nama_obat" name="new_nama_obat" required>
            </div>
            <div class="form-group">
              <label for="update_kemasan">Kemasan</label>
              <input type="text" class="form-control" id="update_kemasan" name="new_kemasan" required>
            </div>
            <div class="form-group">
              <label for="update_harga">Harga</label>
              <input type="text" class="form-control" id="update_harga" name="new_harga" required>
            </div>
            <button type="submit" name="update_obat_modal" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- Modal for adding obat -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Tambah Obat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="menuAdmin.php">
          <!-- Replace with the actual add PHP file -->
          <div class="form-group">
            <label for="add_nama_obat">Nama Obat</label>
            <input type="text" class="form-control" id="add_nama_obat" name="add_nama_obat" required>
          </div>
          <div class="form-group">
            <label for="add_kemasan">Kemasan</label>
            <input type="text" class="form-control" id="add_kemasan" name="add_kemasan" required>
          </div>
          <div class="form-group">
            <label for="add_harga">Harga</label>
            <input type="text" class="form-control" id="add_harga" name="add_harga" required>
          </div>
          <button type="submit" name="add_obat" class="btn btn-primary">Tambah</button>
        </form>
      </div>
    </div>
  </div>
</div>


  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <!-- Add other necessary script includes here -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add your JavaScript code here
      var updateButtons = document.querySelectorAll('.update-btn');

      updateButtons.forEach(function(button) {
        button.addEventListener('click', function() {
          var id = button.getAttribute('data-id');
          var nama_obat = button.getAttribute('data-nama_obat');
          var kemasan = button.getAttribute('data-kemasan');
          var harga = button.getAttribute('data-harga');

          document.getElementById('update_id').value = id;
          document.getElementById('update_nama_obat').value = nama_obat;
          document.getElementById('update_kemasan').value = kemasan;
          document.getElementById('update_harga').value = harga;
        });
      });
    });
  </script>
</body>

</html>