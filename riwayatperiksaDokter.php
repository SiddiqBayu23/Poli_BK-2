<?php
if (!isset($_SESSION)) {
  session_start();
}

// Fetch data from the 'pasien' table
$pasienQuery = "SELECT * FROM pasien";
$pasienResult = $mysqli->query($pasienQuery);

// Fetch the data as an associative array
$pasienData = $pasienResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Add your head section here -->

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function () {
      // Event listener for the "View" button
      $('.view-btn').click(function () {
        var pasienId = $(this).data('id');

        // Ajax request to fetch additional details
        $.ajax({
          type: 'POST',
          url: 'get_details.php',
          data: { pasien_id: pasienId },
          success: function (response) {
            // Display the fetched details in the modal body
            $('#viewModalBody').html(response);

            // Show the modal
            $('#viewModal').modal('show');
          }
        });
      });
    });
  </script>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama</th>
                      <th>Alamat</th>
                      <th>No KTP</th>
                      <th>No Telpon</th>
                      <th>No.RM</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($pasienData as $pasienRow) {
                      echo "<tr>";
                      echo "<td>" . $pasienRow['id'] . "</td>";
                      echo "<td>" . $pasienRow['nama'] . "</td>";
                      echo "<td>" . $pasienRow['alamat'] . "</td>";
                      echo "<td>" . $pasienRow['no_ktp'] . "</td>";
                      echo "<td>" . $pasienRow['no_hp'] . "</td>";
                      echo "<td>" . $pasienRow['no_rm'] . "</td>";
                      echo "<td>
          <button class='btn btn-info view-btn' data-id='" . $pasienRow['id'] . "'>View</button>
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

  <!-- Modal for displaying details -->
  <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">View Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="viewModalBody">
          <!-- Details will be displayed here dynamically -->
        </div>
      </div>
    </div>
  </div>
</body>

</html>
