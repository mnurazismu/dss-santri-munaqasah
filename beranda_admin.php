<?php
session_start();
require 'config.php';

if (!isset($_SESSION['login'])) {
    echo '
    <script src="src/jquery-3.6.3.min.js"></script>
    <script src="src/sweetalert2.all.min.js"></script>
    <script>
    $(document).ready(function() {
        Swal.fire({
            position: "top-center",
            icon: "error",
            title: "Anda Belum Login!",
            text: "Silahkan Login Terlebih Dahulu!",
            showConfirmButton: false,
            timer: 2000
        })
        setTimeout(myFunction, 2000);
    });
    function myFunction() {
        document.location.href = "login.php";
    }
    </script>
    ';
    exit;
} else {
    if ($_SESSION['tipe_user'] != 'Admin') {
        echo '
        <script src="src/jquery-3.6.3.min.js"></script>
        <script src="src/sweetalert2.all.min.js"></script>
        <script>
        $(document).ready(function() {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Anda Login Sebagai Guru!",
                showConfirmButton: false,
                timer: 2000
            })
            setTimeout(myFunction, 2000);
        });
        function myFunction() {
            document.location.href = "beranda_guru.php";
        }
        </script>
        ';
        exit;
    }

    $username = $_SESSION['username'];
    $nama_lengkap = $_SESSION['nama_lengkap'];
    $tipe_user = $_SESSION['tipe_user'];

    $query_kriteria = "SELECT * FROM kriteria";
    $result_kriteria = mysqli_query($conn, $query_kriteria);
    $total_kriteria = mysqli_num_rows($result_kriteria);

    $query_subkriteria = "SELECT * FROM subkriteria";
    $result_subkriteria = mysqli_query($conn, $query_subkriteria);
    $total_subkriteria = mysqli_num_rows($result_subkriteria);

    $query_alternatif = "SELECT * FROM alternatif";
    $result_alternatif = mysqli_query($conn, $query_alternatif);
    $total_alternatif = mysqli_num_rows($result_alternatif);

    $query_pengguna = "SELECT * FROM user";
    $result_pengguna = mysqli_query($conn, $query_pengguna);
    $total_pengguna = mysqli_num_rows($result_pengguna);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/output.css">
    <title>Beranda Admin</title>
</head>

<body class="font-['Inter']">
    <header>
        <?php include 'navbar_admin.php'; ?>
    </header>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <?php include 'sidebar_admin.php'; ?>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-400 text-base dark:text-gray-400"><?php echo date('d F Y'); ?></p>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Selamat Datang, <?= $nama_lengkap; ?></h1>
                </div>
                <p class="text-gray-600 text-sm dark:text-gray-400">Anda login sebagai <?= $tipe_user; ?>.</p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg mt-8 flex justify-around flex-wrap gap-8">
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:min-w-[460px] md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="./src/images/dummy.jpeg" alt="">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Data Kriteria</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Total Data Kriteria: <span class="font-bold text-blue-900"><?= $total_kriteria ?></span></p>
                    </div>
                </a>
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:min-w-[460px] md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="./src/images/dummy.jpeg" alt="">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Data Sub Kriteria</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Total Data Subkriteria: <span class="font-bold text-blue-900"><?= $total_subkriteria ?></span></p>
                    </div>
                </a>
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:min-w-[460px] md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="./src/images/dummy.jpeg" alt="">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Data Alternatif</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Total Data Alternatif: <span class="font-bold text-blue-900"><?= $total_alternatif ?></span></p>
                    </div>
                </a>
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:min-w-[460px] md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="./src/images/dummy.jpeg" alt="">
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Data Pengguna</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Total Data Pengguna: <span class="font-bold text-blue-900"><?= $total_pengguna ?></span></p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>