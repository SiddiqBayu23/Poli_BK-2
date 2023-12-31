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

                <?php
                if (isset($_SESSION['username' . 'nama'])) {
                ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">Logout (<?php echo $_SESSION['username' . 'nama'] ?>)</a>
                        </li>
                    </ul>
                <?php
                } else {
                ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginAdmin">Login Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginDokter">Login Dokter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginPasien">Login Pasien</a>
                        </li>
                    </ul>
                <?php
                }
                ?>
            </div>
        </div>
    </nav>

    <?php
    if (isset($_GET['page'])) {
        if ($_GET['page'] === 'login') {
            include("loginAdmin.php");
        } elseif ($_GET['page'] === 'loginAdmin') {
            include("loginAdmin.php");
        } elseif ($_GET['page'] === 'loginPasien') {
            include("loginPasien.php");
        } else {
            include($_GET['page'] . ".php");
        }
    } else {
        echo "<br><h2>Selamat Datang di Sistem Informasi Poliklinik";

        if (isset($_SESSION['username' . 'nama'])) {
            echo ", " . $_SESSION['username' . 'nama'] . "</h2><hr>";
        } else {
            echo "</h2><hr>Silakan Login untuk menggunakan sistem. Jika belum memiliki akun silakan Register terlebih dahulu.";
        }
    }
    ?>
</body>

</html>
