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

    if (isset($_GET['id_user'])) {
        $id = $_GET['id_user'];
        $query_user = "SELECT * FROM user WHERE id_user = $id";
        $result_user = mysqli_query($conn, $query_user);
        $row = mysqli_fetch_assoc($result_user);

        if (isset($_POST['ubah'])) {
            $nama_lengkap = $_POST['nama_lengkap'];
            $username = $_POST['username'];
            $tipe_user_input = $_POST['tipe_user'];

            $query = "UPDATE user SET nama_lengkap = '$nama_lengkap', username = '$username', tipe_user = '$tipe_user_input' WHERE id_user = $id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                if ($_SESSION['tipe_user'] !== $tipe_user_input) {
                    session_unset();
                    session_destroy();

                    echo '
                        <script src="src/jquery-3.6.3.min.js"></script>
                        <script src="src/sweetalert2.all.min.js"></script>
                        <script>
                        $(document).ready(function() {
                            Swal.fire({
                                position: "top-center",
                                icon: "success",
                                title: "Anda telah logout karena tipe user diubah!",
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
                    echo '
                    <script src="src/jquery-3.6.3.min.js"></script>
                    <script src="src/sweetalert2.all.min.js"></script>
                    <script>
                    $(document).ready(function() {
                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            title: "Data Berhasil Diubah!",
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(myFunction, 2000);
                    });
                    function myFunction() {
                        document.location.href = "pengguna.php";
                    }
                    </script>
                    ';
                    exit;
                }
            } else {
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: "Data Gagal Diubah!",
                        showConfirmButton: false,
                        timer: 2000
                    })
                    setTimeout(myFunction, 2000);
                });
                function myFunction() {
                    document.location.href = "ubah_pengguna.php?id_user=' . $id . '";
                }
                </script>
                ';
                exit;
            }
        }
    } else {
        echo '
        <script src="src/jquery-3.6.3.min.js"></script>
        <script src="src/sweetalert2.all.min.js"></script>
        <script>
        $(document).ready(function() {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "ID Tidak Ditemukan!",
                showConfirmButton: false,
                timer: 2000
            })
            setTimeout(myFunction, 2000);
        });
        function myFunction() {
            document.location.href = "pengguna.php";
        }
        </script>
        ';
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
    <title>Ubah Pengguna</title>
</head>

<body class="font-['Inter'] bg-gray-100 flex min-h-screen justify-center items-center">
    <div class="w-1/2 shadow-md bg-white rounded-md py-12 my-12">
        <form class="max-w-sm mx-auto lg:min-w-full px-12" action="" method="post">
            <h1 class="mb-2 text-2xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white text-shadow">Form Ubah Pengguna</h1>
            <h3 class="mb-8 text-lg">Silahkan lengkapi data di bawah ini.</h3>
            <input type="hidden" name="id" value=<?= $id ?>>
            <div class="mb-5">
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pengguna</label>
                <div class="relative">
                    <input value="<?php echo $row['nama_lengkap'] ?>" type="text" id="nama_lengkap" name="nama_lengkap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nama Pengguna" required autocomplete="off" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <div class="relative">
                    <input value="<?php echo $row['username'] ?>" type="text" id="username" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username" required autocomplete="off" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <label for="tipe_user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                <div class="flex items-center ps-4 border border-gray-200 rounded-lg dark:border-gray-700 mb-1">
                    <input id="admin" type="radio" value="Admin" name="tipe_user" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" <?php if (isset($row['tipe_user']) && $row['tipe_user'] === 'Admin') echo 'checked'; ?>>
                    <label for="admin" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Admin</label>
                </div>
                <div class="flex items-center ps-4 border border-gray-200 rounded-lg dark:border-gray-700">
                    <input id="guru" type="radio" value="Guru" name="tipe_user" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" <?php if (isset($row['tipe_user']) && $row['tipe_user'] === 'Guru') echo 'checked'; ?>>
                    <label for="guru" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Guru</label>
                </div>
            </div>
            <div>
                <button type="submit" name="ubah" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ubah</button>
                <a href="./alternatif_admin.php"><button type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Kembali</button></a>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>