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

    //hapus session alternatif_terpilih
    unset($_SESSION['alternatif_terpilih']);

    $username = $_SESSION['username'];
    $nama_lengkap = $_SESSION['nama_lengkap'];
    $tipe_user = $_SESSION['tipe_user'];

    $queryKriteria = "SELECT * FROM kriteria";
    $resultKriteria = mysqli_query($conn, $queryKriteria);
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($resultKriteria)) {
        $kriteria[] = $row;
    }

    $queryAlternatif = "SELECT * FROM alternatif";
    $resultAlternatif = mysqli_query($conn, $queryAlternatif);
    $alternatif = [];
    while ($row = mysqli_fetch_assoc($resultAlternatif)) {
        $alternatif[] = $row;
    }

    if (isset($_POST['hitung'])) {
        // Pastikan checkbox dipilih sebelum mengakses $_POST['alternatif_terpilih']
        if (isset($_POST['alternatif_terpilih'])) {
            // Loop melalui nilai alternatif yang dipilih
            foreach ($_POST['alternatif_terpilih'] as $selected_alternatif) {
                // Lakukan apa pun yang perlu kamu lakukan dengan nilai alternatif yang dipilih
                // simpan ke dalam array session
                $_SESSION['alternatif_terpilih'][] = $selected_alternatif;
            }
            // Redirect ke halaman lain
            header('Location: perhitungan_moora_admin.php');
        } else {
            echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: "Tidak Ada Alternatif Yang Dipilih!",
                        text: "Silahkan Pilih Alternatif Terlebih Dahulu!",
                        showConfirmButton: false,
                        timer: 2000
                    })
                    setTimeout(myFunction, 2000);
                });
                function myFunction() {
                    document.location.href = "perhitungan_admin.php";
                }
                </script>
                ';
            exit;
        }
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
    <title>Perhitungan Admin</title>
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
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Data Perhitungan</h1>
                </div>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg mt-8 flex flex-col">
                <form action="" method="post">
                    <div class="mb-2 flex justify-between">
                        <button type="submit" name="hitung" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                            </svg>
                            Hitung MOORA
                        </button>
                        <div class="mb-2 flex justify-between">
                            <div class="flex items-center me-4" onclick="toggleSelectAll(this)">
                                <input id="green-checkbox" type="checkbox" value="" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="green-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Select All</label>
                            </div>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-sm text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nama Alternatif
                                    </th>
                                    <?php foreach ($kriteria as $key => $value) : ?>
                                        <th scope="col" class="px-6 py-3">
                                            C<?= $key + 1 ?>
                                        </th>
                                    <?php endforeach; ?>
                                    <th scope="col" class="px-6 py-3">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alternatif as $key => $value) : ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th class="px-6 py-4">
                                            <?= $key + 1 ?>
                                        </th>
                                        <?php
                                        $id_alternatif = $value['id_alternatif'];
                                        $query_matriks = "SELECT * FROM nilai_matriks WHERE id_alternatif = '$id_alternatif'";
                                        $result_matriks = mysqli_query($conn, $query_matriks);
                                        $nilai_matriks = [];
                                        while ($row = mysqli_fetch_assoc($result_matriks)) {
                                            $nilai_matriks[] = $row;
                                        }
                                        ?>
                                        <td class="px-6 py-4">
                                            <?= $value['nama_alternatif'] ?>
                                        </td>
                                        <?php foreach ($nilai_matriks as $key => $value) : ?>
                                            <td class="px-6 py-4">
                                                <?= $value['nilai_matriks'] ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center ps-3">
                                                <!-- Tambahkan atribut name dan value pada tag input -->
                                                <input id="pilih<?= $key ?>" type="checkbox" name="alternatif_terpilih[]" value="<?= $value['id_alternatif'] ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                <label for="pilih<?= $key ?>" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pilih</label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        function toggleSelectAll() {
            // Mengambil semua checkbox
            var checkboxes = document.querySelectorAll('input[name="alternatif_terpilih[]"]');

            // Mengecek apakah setidaknya satu checkbox tercentang
            var atLeastOneChecked = false;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    atLeastOneChecked = true;
                }
            });

            // Jika setidaknya satu checkbox tercentang, maka unselect all
            if (atLeastOneChecked) {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                document.querySelector('button[type="button"]').textContent = 'Select All';
            }
            // Jika tidak ada yang tercentang, maka select all
            else {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
                document.querySelector('button[type="button"]').textContent = 'Unselect All';
            }
        }
    </script>

</body>

</html>