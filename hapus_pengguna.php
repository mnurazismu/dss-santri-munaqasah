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
        $query_user = "DELETE FROM user WHERE id_user = '$id'";
        $result = mysqli_query($conn, $query_user);
        if ($result) {
            $query_check_user = "SELECT * FROM user WHERE id_user = '$id'";
            $result_check_user = mysqli_query($conn, $query_check_user);
            if (mysqli_num_rows($result_check_user) == 0) {
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
                                title: "Anda telah logout karena user anda telah dihapus!",
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
                        icon: "error",
                        title: "Data Pengguna Gagal Dihapus!",
                        showConfirmButton: false,
                        timer: 2000
                    })
                    setTimeout(myFunction, 2000);
                });
                function myFunction() {
                    document.location.href = "data_pengguna.php";
                }
                </script>
                ';
            }
            echo '
            <script src="src/jquery-3.6.3.min.js"></script>
            <script src="src/sweetalert2.all.min.js"></script>
            <script>
            $(document).ready(function() {
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    title: "Data Pengguna Berhasil Dihapus!",
                    showConfirmButton: false,
                    timer: 2000
                })
                setTimeout(myFunction, 2000);
            });
            function myFunction() {
                document.location.href = "data_pengguna.php";
            }
            </script>
            ';
        } else {
            echo '
            <script src="src/jquery-3.6.3.min.js"></script>
            <script src="src/sweetalert2.all.min.js"></script>
            <script>
            $(document).ready(function() {
                Swal.fire({
                    position: "top-center",
                    icon: "error",
                    title: "Data Pengguna Gagal Dihapus!",
                    showConfirmButton: false,
                    timer: 2000
                })
                setTimeout(myFunction, 2000);
            });
            function myFunction() {
                document.location.href = "data_pengguna.php";
            }
            </script>
            ';
        }
    }
}
