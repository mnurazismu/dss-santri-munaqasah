<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'spk-santri';

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die('Koneksi Gagal: ' . mysqli_connect_error());
}
