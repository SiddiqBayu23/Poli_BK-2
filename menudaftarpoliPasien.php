<?php
if (!isset($_SESSION)) {
    session_start();
}

include_once("koneksi.php");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistem Informasi Poliklinik</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    </li>
                    <li class="nav-item">
                        <?php
                        if (isset($_SESSION['nama'])) {
                            // Jika pengguna sudah login, tampilkan tombol "Logout"
                        ?>
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="logout.php">Logout (<?php echo $_SESSION['nama'] ?>)</a>
                                </li>
                            </ul>
                        <?php
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // Check if the requested page is set
    if (isset($_GET['page'])) {
        // Check if the requested page is login, and if so, include loginDokter.php
        if ($_GET['page'] === 'loginPasien') {
            include("loginDokter.php");
        } elseif ($_GET['page'] === 'daftarpoliPasien') {
            // Include riwayatperiksaDokter.php for the 'riwayatperiksa' page
            include("daftarpoliPasien.php");
        } else {
            // Include other pages based on the value of $_GET['page']
            include($_GET['page'] . ".php");
        }
    } else {
        // If no specific page is requested, include a default content
        include("daftarpoliPasien.php");
    }
    ?>
</body>

</html>