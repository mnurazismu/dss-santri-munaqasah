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

    if (isset($_POST['selanjutnya'])) {
        $nama_kriteria = $_POST['nama_kriteria'];
        $bobot_kriteria = $_POST['bobot_kriteria'];
        $jenis_kriteria = $_POST['jenis_kriteria'];
        $jumlah_subkriteria = $_POST['jumlah_subkriteria'];

        $_SESSION['nama_kriteria'] = $nama_kriteria;
        $_SESSION['bobot_kriteria'] = $bobot_kriteria;
        $_SESSION['jenis_kriteria'] = $jenis_kriteria;
        $_SESSION['jumlah_subkriteria'] = $jumlah_subkriteria;
        header('Location: tambah_subkriteria.php');
        exit;
    }
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
    <title>Tambah Kriteria</title>
</head>

<body class="font-['Inter'] bg-gray-100 flex min-h-screen justify-center items-center">
    <div class="w-1/3 shadow-md bg-white rounded-md py-12">
        <form class="max-w-sm mx-auto lg:min-w-96" action="" method="post">
            <h1 class="mb-2 text-2xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white text-shadow">Form Tambah Kriteria</h1>
            <h3 class="mb-8 text-lg">Silahkan lengkapi data di bawah ini.</h3>
            <div class="mb-5">
                <label for="nama_kriteria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kriteria</label>
                <div class="relative">
                    <input type="text" id="nama_kriteria" name="nama_kriteria" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nama Kriteria" required autocomplete="off" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label for="bobot_kriteria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bobot Kriteria</label>
                <div class="relative">
                    <input type="number" id="bobot_kriteria" name="bobot_kriteria" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Bobot Kriteria" required autocomplete="off" min="1" max="100" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label for="jenis_kriteria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kriteria</label>
                <div class="flex items-center ps-4 border border-gray-200 rounded-lg dark:border-gray-700 mb-1">
                    <input id="benefit" type="radio" value="Benefit" name="jenis_kriteria" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="benefit" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Benefit</label>
                </div>
                <div class="flex items-center ps-4 border border-gray-200 rounded-lg dark:border-gray-700">
                    <input id="cost" type="radio" value="Cost" name="jenis_kriteria" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="cost" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cost</label>
                </div>
            </div>
            <div class="mb-5">
                <label for="jumlah_subkriteria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Sub Kriteria</label>
                <div class="relative">
                    <input type="number" id="jumlah_subkriteria" name="jumlah_subkriteria" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Jumlah Sub Kriteria" required autocomplete="off" min="1" max="5" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" name="selanjutnya" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Selanjutnya</button>
                <a href="./kriteria_admin.php"><button type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Kembali</button></a>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>