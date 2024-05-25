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
    if ($_SESSION['tipe_user'] != 'Guru') {
        echo '
        <script src="src/jquery-3.6.3.min.js"></script>
        <script src="src/sweetalert2.all.min.js"></script>
        <script>
        $(document).ready(function() {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Anda Login Sebagai Admin!",
                showConfirmButton: false,
                timer: 2000
            })
            setTimeout(myFunction, 2000);
        });
        function myFunction() {
            document.location.href = "beranda_admin.php";
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
    $jumlah_subkriteria = mysqli_num_rows($result_kriteria);
    $kriteria = [];
    while ($row_kriteria = mysqli_fetch_assoc($result_kriteria)) {
        $kriteria[] = $row_kriteria;
    }

    if (isset($_POST['tambah'])) {
        $nama_alternatif = $_POST['nama_alternatif'];
        $query = "INSERT INTO alternatif (nama_alternatif) VALUES ('$nama_alternatif')";
        $result = mysqli_query($conn, $query);
        $query2 = "SELECT MAX(id_alternatif) as id_alternatif FROM alternatif";
        $result2 = mysqli_query($conn, $query2);
        $row = mysqli_fetch_assoc($result2);
        $id_alternatif = $row['id_alternatif'];

        foreach ($kriteria as $key) {
            $nilai = $_POST['c' . $key['id_kriteria']];
            $query3 = "INSERT INTO nilai_matriks (id_alternatif, id_kriteria, nilai_matriks) VALUES ('$id_alternatif', '" . $key['id_kriteria'] . "', '$nilai')";
            $result3 = mysqli_query($conn, $query3);
        }
        if ($result) {
            echo '
            <script src="src/jquery-3.6.3.min.js"></script>
            <script src="src/sweetalert2.all.min.js"></script>
            <script>
            $(document).ready(function() {
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    title: "Berhasil!",
                    text: "Alternatif berhasil ditambahkan!",
                    showConfirmButton: false,
                    timer: 2000
                })
                setTimeout(myFunction, 2000);
            });
            function myFunction() {
                document.location.href = "alternatif_guru.php";
            }
            </script>
            ';
            exit;
        } else {
            echo '
            <script src="src/jquery-3.6.3.min.js"></script>
            <script src="src/sweetalert2.all.min.js"></script>
            <script>
            $(document).ready(function() {
                Swal.fire({
                    position: "top-center",
                    icon: "error",
                    title: "Gagal!",
                    text: "Alternatif gagal ditambahkan!",
                    showConfirmButton: false,
                    timer: 2000
                })
                setTimeout(myFunction, 2000);
            });
            function myFunction() {
                document.location.href = "tambah_alternatif_guru.php";
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
    <title>Tambah Alternatif</title>
</head>

<body class="font-['Inter'] bg-gray-100 flex min-h-screen justify-center items-center">
    <div class="w-1/2 shadow-md bg-white rounded-md py-12 my-12">
        <form class="max-w-sm mx-auto lg:min-w-full px-12" action="" method="post">
            <h1 class="mb-2 text-2xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white text-shadow">Form Tambah Alternatif</h1>
            <h3 class="mb-8 text-lg">Silahkan lengkapi data di bawah ini.</h3>
            <div class="mb-5">
                <label for="nama_alternatif" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Alternatif</label>
                <div class="relative">
                    <input type="text" id="nama_alternatif" name="nama_alternatif" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nama Alternatif" required autocomplete="off" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            <div class="mx-auto mb-4">
                <?php foreach ($kriteria as $key) : ?>
                    <div class="flex flex-col w-full">
                        <table>
                            <tr class="w-full">
                                <td class="w-1/2 text-left py-3">
                                    <label for="c<?= $key['id_kriteria'] ?>" class="font-semibold">
                                        <?= $key['nama_kriteria'] ?>
                                    </label>
                                </td>
                                <td class="w-1/2">
                                    <select name="c<?= $key['id_kriteria'] ?>" id="c<?= $key['id_kriteria'] ?>" class="bg-gray-50 font-semibold border border-gray-300 text-sm rounded-lg focus:outline-offset-4 focus:outline-secondary block w-full p-2.5 " required>
                                        <option value="" disabled selected class="text-center">Pilih</option>
                                        <?php
                                        $query_subkriteria = "SELECT * FROM subkriteria WHERE id_kriteria = " . $key['id_kriteria'];
                                        $result_subkriteria = mysqli_query($conn, $query_subkriteria);
                                        $subkriteria = [];
                                        while ($row_subkriteria = mysqli_fetch_assoc($result_subkriteria)) {
                                            $subkriteria[] = $row_subkriteria;
                                        }
                                        foreach ($subkriteria as $row) : ?>
                                            <option value="<?= $row['nilai_sub'] ?>" class="text-center"><?= $row['nilai_sub'] ?> | <?= $row['nama_sub'] ?></option>
                                        <?php endforeach;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
            <div>
                <button type="submit" name="tambah" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                <a href="./alternatif_guru.php"><button type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Kembali</button></a>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>