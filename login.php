<?php
session_start();
require 'config.php';

if (isset($_POST['masuk'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek_username = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    if (mysqli_num_rows($cek_username) === 1) {
        $row = mysqli_fetch_assoc($cek_username);
        if (password_verify($password, $row['password'])) {
            if ($row['tipe_user'] == 'Guru') {
                $_SESSION['login'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['tipe_user'] = $row['tipe_user'];
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        title: "Login Berhasil!",
                        text: "Anda login sebagai ' . $row['tipe_user'] . '",
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
            } elseif ($row['tipe_user'] == 'Admin') {
                $_SESSION['login'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['tipe_user'] = $row['tipe_user'];
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        title: "Login Berhasil!",
                        text: "Anda login sebagai ' . $row['tipe_user'] . '",
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
        } elseif ($password == $row['password']) {
            if ($row['tipe_user'] == 'Guru') {
                $_SESSION['login'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['tipe_user'] = $row['tipe_user'];
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        title: "Login Berhasil!",
                        text: "Anda login sebagai ' . $row['tipe_user'] . '",
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
            } elseif ($row['tipe_user'] == 'Admin') {
                $_SESSION['login'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['tipe_user'] = $row['tipe_user'];
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        title: "Login Berhasil!",
                        text: "Anda login sebagai ' . $row['tipe_user'] . '",
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
        } else {
            echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: "Login Gagal!",
                        text: "Password Salah!",
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
                    title: "Login Gagal!",
                    text: "Username Tidak Terdaftar!",
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
    <title>Login</title>
</head>

<body class="font-['Inter']">
    <main class="min-h-screen flex flex-col lg:flex-row">
        <section class="cursor-pointer flex lg:w-1/2 bg-white dark:bg-gray-900 bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern.svg')] dark:bg-[url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern-dark.svg')]">
            <a href="./index.php" class="h-96 w-96 bg-gradient-to-b from-blue-100 to-blue-50 border border-blue-100 shadow-sm rounded m-auto flex flex-col gap-6 justify-center items-center">
                <h3 class="font-semibold text-lg text-gray-500 tracking-wider">Sistem Pendukung Keputusan</h3>
                <h3 class="font-bold text-2xl">Pemilihan Santri Munaqasah</h3>
                <h3>TPA Al - Munawwaroh</h3>
            </a>
        </section>
        <section class="lg:w-1/2 flex flex-col items-center justify-center">
            <form class="max-w-sm mx-auto lg:min-w-96" action="" method="post">
                <h1 class="mb-2 text-4xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-white text-shadow">Halo👋 silahkan login.</h1>
                <h3 class="mb-8">Masukkan data anda di bawah ini.</h3>
                <div class="mb-5">
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <div class="relative">
                        <input type="text" id="username" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username" required autocomplete="off" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Password" required autocomplete="off" />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400">
                                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="show_password" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                    </div>
                    <label for="show_password" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lihat Password</label>
                </div>
                <button type="submit" name="masuk" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Masuk</button>
            </form>
            <p class="mt-12 inline-flex justify-between items-center py-1 px-1 mb-7 text-sm text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                <span class="text-sm font-medium ml-2">Belum punya akun?</span> <a href="regis.php" class="text-xs bg-blue-600 rounded-full text-white px-5 py-2.5 ml-4 hover:cursor-pointer hover:bg-blue-800">Daftar</a>
            </p>
        </section>
        <div class="bg-gradient-to-b from-blue-100 to-transparent dark:from-blue-900 w-full h-full absolute top-0 left-0 -z-50"></div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPasswordCheckbox = document.getElementById('show_password');
            const passwordInput = document.getElementById('password');

            showPasswordCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });
        });
    </script>

</body>

</html>