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

    if (isset($_SESSION['ubahprofil'])) {
        $ubah_namalengkap = $_SESSION['ubah_namalengkap'];
        $ubah_username = $_SESSION['ubah_username'];
        $ubah_passwordlama = $_SESSION['passwordlama'];
        $ubah_passwordbaru = $_SESSION['passwordbaru'];
        $query = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);


        if (password_verify($ubah_passwordlama, $row['password'])) {
            $passwordbaru = password_hash($ubah_passwordbaru, PASSWORD_DEFAULT);
            $query = "UPDATE user SET nama_lengkap = '$ubah_namalengkap', username = '$ubah_username', password = '$passwordbaru' WHERE username = '$ubah_username'";
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        title: "Berhasil Ubah Data Profil!",
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
            } else {
                echo '
                <script src="src/jquery-3.6.3.min.js"></script>
                <script src="src/sweetalert2.all.min.js"></script>
                <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: "Gagal Ubah Data Profil!",
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
                    title: "Password Lama Salah!",
                    showConfirmButton
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
    }
}
