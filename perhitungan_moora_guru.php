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

    $queryKriteria = "SELECT * FROM kriteria";
    $resultKriteria = mysqli_query($conn, $queryKriteria);
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($resultKriteria)) {
        $kriteria[] = $row;
    }
    $jumlah_bobot_kriteria = 0;
    foreach ($kriteria as $key) {
        $jumlah_bobot_kriteria += $key['bobot_kriteria'];
    }

    if ($jumlah_bobot_kriteria != 100) {
        echo '
        <script src="src/jquery-3.6.3.min.js"></script>
        <script src="src/sweetalert2.all.min.js"></script>
        <script>
        $(document).ready(function() {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Jumlah Bobot Kriteria Tidak 100%!",
                text: "Silahkan Hubungi Admin Untuk Mengatur Bobot Kriteria Terlebih Dahulu!",
                showConfirmButton: false,
                timer: 2000
            })
            setTimeout(myFunction, 2000);
        });
        function myFunction() {
            document.location.href = "perhitungan_guru.php";
        }
        </script>
        ';
        exit;
    }

    $selected_alternatif = [];
    foreach ($_SESSION['alternatif_terpilih'] as $key) {
        $selected_alternatif[] = $key;
    }

    $query_alternatif = "SELECT * FROM alternatif WHERE id_alternatif IN (" . implode(',', $selected_alternatif) . ")";
    $result_alternatif = mysqli_query($conn, $query_alternatif);
    $alternatif = [];
    while ($row = mysqli_fetch_assoc($result_alternatif)) {
        $alternatif[] = $row;
    }

    $query_check = "SELECT COUNT(*) as count FROM nilai_matriks WHERE nilai_matriks = 0 AND id_alternatif IN (" . implode(',', $selected_alternatif) . ")";
    $result_check = mysqli_query($conn, $query_check);
    $check = mysqli_fetch_assoc($result_check);
    $has_zero = $check['count'] > 0;

    if ($has_zero) {
        echo '
        <script src="src/jquery-3.6.3.min.js"></script>
        <script src="src/sweetalert2.all.min.js"></script>
        <script>
        $(document).ready(function() {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Nilai Matriks Ada Yang Bernilai 0!",
                text: "Silahkan Isi Nilai Matriks Terlebih Dahulu!",
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
    }

    $query_reset_hasil = "UPDATE hasil SET nilai_hasil = 0";
    mysqli_query($conn, $query_reset_hasil);
    $query_nilai_matriks = "SELECT nilai_matriks.*, kriteria.jenis_kriteria , kriteria.bobot_kriteria
                        FROM nilai_matriks 
                        INNER JOIN kriteria ON nilai_matriks.id_kriteria = kriteria.id_kriteria 
                        WHERE nilai_matriks.id_alternatif IN (" . implode(',', $selected_alternatif) . ")";
    $result_nilai_matriks = mysqli_query($conn, $query_nilai_matriks);
    $nilai_matriks = [];
    while ($row = mysqli_fetch_assoc($result_nilai_matriks)) {
        $nilai_matriks[] = $row;
    }
    $id_kriteria = [];
    foreach ($nilai_matriks as $key) {
        $id_kriteria[] = $key['id_kriteria'];
    }
    $query_kriteria = "SELECT * FROM kriteria WHERE id_kriteria IN (" . implode(',', $id_kriteria) . ")";
    $result_kriteria = mysqli_query($conn, $query_kriteria);
    $jumlah_kriteria = mysqli_num_rows($result_kriteria);
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($result_kriteria)) {
        $kriteria[] = $row;
    }

    $nilai_matriks_keputusan = [];
    foreach ($nilai_matriks as $key => $value) {
        $id_kriteria = $value['id_kriteria'];
        $nilai_matriks_keputusan[$id_kriteria]['jenis_kriteria'] = $value['jenis_kriteria'];
        $nilai_matriks_keputusan[$id_kriteria]['nilai_matriks'][] = $value['nilai_matriks'];
    }

    // Inisialisasi array untuk menyimpan nilai matriks ternormalisasi
    $nilai_matriks_ternormalisasi = [];

    // Normalisasi nilai matriks keputusan
    foreach ($nilai_matriks_keputusan as $key => $value) {
        // Ambil nilai matriks untuk kriteria saat ini
        $nilai_kriteria = $value['nilai_matriks'];

        // Hitung jumlah kuadrat dari setiap nilai matriks
        $sum_of_squares = array_sum(array_map(function ($x) {
            return $x ** 2;
        }, $nilai_kriteria));

        // Normalisasi setiap nilai matriks
        foreach ($nilai_kriteria as $nilai) {
            $nilai_matriks_ternormalisasi[$key]['jenis_kriteria'] = $value['jenis_kriteria']; // Tambahkan jenis kriteria ke dalam array ternormalisasi
            $nilai_matriks_ternormalisasi[$key]['nilai_matriks'][] = $nilai / sqrt($sum_of_squares);
        }
    }

    // Format nilai matriks ternormalisasi menjadi hanya 6 angka di belakang koma
    foreach ($nilai_matriks_ternormalisasi as $key => $value) {
        $nilai_matriks_ternormalisasi[$key]['nilai_matriks'] = array_map(function ($x) {
            return round($x, 6);
        }, $value['nilai_matriks']);
    }

    // Inisialisasi array untuk menyimpan bobot kriteria yang telah dinormalisasi
    $normalisasi_bobot = [];

    // Mengambil bobot kriteria
    foreach ($kriteria as $key => $value) {
        $normalisasi_bobot[] = $value['bobot_kriteria'] / 100;
    }

    // Ubah array bobot kriteria menjadi multi dimensi
    $normalisasi_bobot = array_map(function ($x) {
        return [$x];
    }, $normalisasi_bobot);

    // buat key terurut untuk array bobot kriteria
    $keys = range(1, count($normalisasi_bobot));
    $normalisasi_bobot = array_combine($keys, $normalisasi_bobot);

    // buat key terurut untuk array nilai matriks ternormalisasi
    $key = range(1, count($nilai_matriks_ternormalisasi));
    $nilai_matriks_ternormalisasi = array_combine($key, $nilai_matriks_ternormalisasi);

    // Lakukan perhitungan optimalisasi nilai atribut dengan mengalikan nilai matriks ternormalisasi dengan bobot kriteria
    $nilai_matriks_optimalisasi = [];

    foreach ($nilai_matriks_ternormalisasi as $key => $value) {
        // Ambil jenis kriteria dari array nilai_matriks_ternormalisasi
        $jenis_kriteria = $value['jenis_kriteria'];

        // Lakukan perkalian nilai dari matriks ternormalisasi dengan bobot
        foreach ($value['nilai_matriks'] as $index => $nilai) {
            // Perkalian nilai dari matriks ternormalisasi dengan bobot
            $nilai_matriks_optimalisasi[$key]['jenis_kriteria'] = $jenis_kriteria; // Tetapkan jenis kriteria
            $nilai_matriks_optimalisasi[$key]['nilai_matriks'][$index] = $nilai * $normalisasi_bobot[$key][0];
        }
    }

    // Format nilai matriks optimalisasi menjadi hanya 6 angka di belakang koma
    foreach ($nilai_matriks_optimalisasi as $key => $value) {
        $nilai_matriks_optimalisasi[$key]['nilai_matriks'] = array_map(function ($x) {
            return round($x, 6);
        }, $value['nilai_matriks']);
    }

    $nilai_matriks_benefit = [];
    $nilai_matriks_cost = [];

    foreach ($nilai_matriks_optimalisasi as $key => $value) {
        if ($value['jenis_kriteria'] === 'Benefit') {
            $nilai_matriks_benefit[] = $value['nilai_matriks'];
        } elseif ($value['jenis_kriteria'] === 'Cost') {
            $nilai_matriks_cost[] = $value['nilai_matriks'];
        }
    }

    $hasil_jumlah_benefit = [];
    $hasil_jumlah_cost = [];

    // Penjumlahan untuk kriteria dengan jenis "Benefit"
    foreach ($nilai_matriks_benefit as $nilai) {
        foreach ($nilai as $index => $nilai_single) {
            if (!isset($hasil_jumlah_benefit[$index])) {
                $hasil_jumlah_benefit[$index] = 0;
            }
            $hasil_jumlah_benefit[$index] += $nilai_single;
        }
    }

    // Penjumlahan untuk kriteria dengan jenis "Cost"
    foreach ($nilai_matriks_cost as $nilai) {
        foreach ($nilai as $index => $nilai_single) {
            if (!isset($hasil_jumlah_cost[$index])) {
                $hasil_jumlah_cost[$index] = 0;
            }
            $hasil_jumlah_cost[$index] += $nilai_single;
        }
    }

    // Menginisialisasi nilai untuk kriteria yang tidak sesuai
    foreach ($hasil_jumlah_benefit as $index => $nilai) {
        if (!isset($hasil_jumlah_cost[$index])) {
            $hasil_jumlah_cost[$index] = 0;
        }
    }

    foreach ($hasil_jumlah_cost as $index => $nilai) {
        if (!isset($hasil_jumlah_benefit[$index])) {
            $hasil_jumlah_benefit[$index] = 0;
        }
    }

    // Gabungkan hasil penjumlahan "Benefit" dan "Cost"
    $hasil_jumlah = [
        'Benefit' => $hasil_jumlah_benefit,
        'Cost' => $hasil_jumlah_cost
    ];


    // // Lakukan penjumlahan untuk kriteria "Benefit"
    // foreach ($nilai_matriks_benefit as $nilai) {
    //     foreach ($nilai as $index => $nilai_single) {
    //         if (!isset ($hasil_jumlah['Benefit'][$index])) {
    //             $hasil_jumlah['Benefit'][$index] = 0;
    //         }
    //         $hasil_jumlah['Benefit'][$index] += $nilai_single;
    //     }
    // }

    // // Lakukan penjumlahan untuk kriteria "Cost"
    // foreach ($nilai_matriks_cost as $nilai) {
    //     foreach ($nilai as $index => $nilai_single) {
    //         if (!isset ($hasil_jumlah['Cost'][$index])) {
    //             $hasil_jumlah['Cost'][$index] = 0;
    //         }
    //         $hasil_jumlah['Cost'][$index] += $nilai_single;
    //     }
    // }

    // Inisialisasi array kosong untuk hasil pengurangan
    $hasil_pengurangan = [];

    // Lakukan pengurangan setiap pasang nilai dari kriteria "Benefit" dan "Cost"
    foreach ($hasil_jumlah['Benefit'] as $index => $nilai_benefit) {
        if (isset($hasil_jumlah['Cost'][$index])) {
            $hasil_pengurangan[$index] = $nilai_benefit - $hasil_jumlah['Cost'][$index];
            // echo $nilai_benefit . ' - ' . $hasil_jumlah['Cost'][$index] . ' = ' . $hasil_pengurangan[$index] . '<br>';
        }
    }

    // Inisialisasi array kosong untuk menyimpan peringkat
    $peringkat = [];

    // Inisialisasi peringkat awal
    $ranking = 1;

    // Berikan peringkat pada setiap hasil pengurangan
    foreach ($hasil_pengurangan as $index => $nilai) {
        $peringkat[$index] = $ranking;
        $ranking++;
    }

    // Inisialisasi array untuk menyimpan nilai matriks ternormalisasi
    // $nilai_matriks_ternormalisasi = [];

    // // Normalisasi nilai matriks keputusan
    // foreach ($nilai_matriks_keputusan as $key => $value) {
    //     // Ambil nilai matriks untuk kriteria saat ini
    //     $nilai_kriteria = $value['nilai_matriks'];

    //     // Hitung jumlah kuadrat dari setiap nilai matriks
    //     $sum_of_squares = array_sum(array_map(function ($x) {
    //         return $x ** 2;
    //     }, $nilai_kriteria));

    //     // Normalisasi setiap nilai matriks
    //     foreach ($nilai_kriteria as $nilai) {
    //         $nilai_matriks_ternormalisasi[$key][] = $nilai / sqrt($sum_of_squares);
    //     }
    // }

    // // Format nilai matriks ternormalisasi menjadi hanya 6 angka di belakang koma
    // foreach ($nilai_matriks_ternormalisasi as $key => $value) {
    //     $nilai_matriks_ternormalisasi[$key] = array_map(function ($x) {
    //         return round($x, 6);
    //     }, $value);
    // }

    // // Inisialisasi array untuk menyimpan bobot kriteria yang telah dinormalisasi
    // $normalisasi_bobot = [];

    // // Mengambil bobot kriteria
    // foreach ($kriteria as $key => $value) {
    //     $normalisasi_bobot[] = $value['bobot_kriteria'] / 100;
    // }

    // // Ubah array bobot kriteria menjadi multi dimensi
    // $normalisasi_bobot = array_map(function ($x) {
    //     return [$x];
    // }, $normalisasi_bobot);

    // $keys = range(1, count($normalisasi_bobot));
    // $normalisasi_bobot = array_combine($keys, $normalisasi_bobot);

    // $key = range(1, count($nilai_matriks_ternormalisasi));
    // $nilai_matriks_ternormalisasi = array_combine($key, $nilai_matriks_ternormalisasi);

    // // Lakukan perhitungan optimalisasi nilai atribut dengan mengalikan nilai matriks ternormalisasi dengan bobot kriteria
    // $nilai_matriks_optimalisasi = [];
    // foreach ($nilai_matriks_ternormalisasi as $i => $baris) {
    //     foreach ($baris as $j => $nilai) {
    //         // Perkalian nilai dari matriks ternormalisasi dengan bobot
    //         $nilai_matriks_optimalisasi[$i][$j] = $nilai * $normalisasi_bobot[$i][0];
    //         // Cetak hasil perkalian jika perlu
    //         // echo $nilai . ' * ' . $normalisasi_bobot[$i][0] . ' = ' . $nilai_matriks_optimalisasi[$i][$j] . '<br>';
    //     }
    // }

    // // Inisialisasi array untuk menyimpan hasil penjumlahan
    // $hasil_jumlah = [];

    // // Perulangan untuk menjumlahkan elemen dengan indeks yang sama
    // foreach ($nilai_matriks_optimalisasi as $array) {
    //     foreach ($array as $index => $nilai) {
    //         // Jika indeks belum ada dalam hasil_jumlah, inisialisasi dengan nilai 0
    //         if (!isset ($hasil_jumlah[$index])) {
    //             $hasil_jumlah[$index] = 0;
    //         }
    //         // Tambahkan nilai ke hasil_jumlah
    //         $hasil_jumlah[$index] += $nilai;
    //     }
    // }
    ////////////////////////////////////////////////////////////////////////
    // $nilai_matriks_ternormalisasi = [];
    // foreach ($nilai_matriks_keputusan as $key => $value) {
    //     $nilai_matriks_ternormalisasi[$key] = [];
    //     foreach ($value as $kunci => $nilai) {
    //         $nilai_matriks_ternormalisasi[$key][] = $nilai / sqrt(array_sum(array_map(function ($x) {
    //             return $x ** 2;
    //         }, $value)));
    //     }
    // }
    // //format nilai matriks ternormalisasi menjadi hanya 6 angka di belakang koma
    // foreach ($nilai_matriks_ternormalisasi as $key => $value) {
    //     $nilai_matriks_ternormalisasi[$key] = array_map(function ($x) {
    //         return round($x, 6);
    //     }, $value);
    // }

    // $normalisasi_bobot = [];
    // foreach ($kriteria as $key => $value) {
    //     $normalisasi_bobot[] = $value['bobot_kriteria'] / 100;
    // }
    // //jadikan array di atas menjadi multi dimensi
    // $normalisasi_bobot = array_map(function ($x) {
    //     return [$x];
    // }, $normalisasi_bobot);

    // $keys = range(1, count($normalisasi_bobot));
    // $normalisasi_bobot = array_combine($keys, $normalisasi_bobot);

    // $key = range(1, count($nilai_matriks_ternormalisasi));
    // $nilai_matriks_ternormalisasi = array_combine($key, $nilai_matriks_ternormalisasi);

    // // lakukan perhitungan optimalisasi nilai atribut dengan mengalikan nilai matriks ternormalisasi dengan bobot kriteria
    // $nilai_matriks_optimalisasi = [];
    // foreach ($nilai_matriks_ternormalisasi as $i => $baris) {
    //     foreach ($baris as $j => $nilai) {
    //         // Perkalian nilai dari matriks ternormalisasi dengan bobot
    //         $nilai_matriks_optimalisasi[$i][$j] = $nilai * $normalisasi_bobot[$i][0];
    //         // Cetak hasil perkalian
    //         // echo $nilai . ' * ' . $normalisasi_bobot[$i][0] . ' = ' . $nilai_matriks_optimalisasi[$i][$j] . '<br>';
    //     }
    // }

    // // lakukan perhitungan nilai max dan min dari matriks optimalisasi
    // $max = [];
    // $min = [];
    // foreach ($nilai_matriks_optimalisasi as $i => $baris) {
    //     // jumlahkan nilai dari matriks optimalisasi yang kriterianya berjenis benefit
    //     $max[] = max($baris);
    //     // jumlahkan nilai dari matriks optimalisasi yang kriterianya berjenis cost
    //     $min[] = min($baris);
    //     // echo 'Max = ' . max($baris) . '<br>';
    // }

    // // lakukan perhitungan nilai max - min
    // $max_min = [];
    // foreach ($max as $i => $value) {
    //     $max_min[] = $value - $min[$i];
    // }

    // // lakukan perhitungan nilai rank
    // $rank = [];
    // foreach ($max_min as $i => $value) {
    //     $rank[] = array_search($value, $max_min) + 1;

    //     // Cetak hasil perhitungan
    //     // echo $value . ' = ' . $rank[$i] . '<br>';
    // }

    // // Inisialisasi array untuk menyimpan hasil penjumlahan
    // $hasil_jumlah = [];

    // // Perulangan untuk menjumlahkan elemen dengan indeks yang sama
    // foreach ($nilai_matriks_optimalisasi as $array) {
    //     foreach ($array as $index => $nilai) {
    //         // Jika indeks belum ada dalam hasil_jumlah, inisialisasi dengan nilai 0
    //         if (!isset ($hasil_jumlah[$index])) {
    //             $hasil_jumlah[$index] = 0;
    //         }
    //         // Tambahkan nilai ke hasil_jumlah
    //         $hasil_jumlah[$index] += $nilai;
    //     }
    // }
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
    <title>Perhitungan MOORA</title>
</head>

<body class="font-['Inter'] bg-gray-100 flex flex-col min-h-screen">
    <h2 class="mx-auto text-xl font-semibold my-5 shadow-xl tracking-widest border-b border-secondary text-center">
        Perhitungan Metode MOORA (Multi Objective Optimization On The Basic Of Ratio)</h2>
    <div class="w-5/6 shadow-md bg-white rounded-md pb-5 mx-auto mb-10">
        <h2 class="mx-auto my-5 tracking-widest border-b border-blue-500 text-center">Matriks Keputusan</h2>
        <?php
        // foreach ($nilai_matriks_keputusan as $key => $value) {
        //     echo '<pre>';
        //     print_r($value);
        //     echo '</pre>';
        // }
        // echo '<pre>';
        // var_dump($nilai_matriks_keputusan);
        // echo '</pre>';
        // echo '<pre>';
        // var_dump($nilai_matriks_ternormalisasi);
        // echo '</pre>';
        // echo '<pre>';
        // var_dump($hasil_jumlah);
        // echo '</pre>';

        ?>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="">
                        <th class=""></th>
                        <th scope="col" colspan="<?= $jumlah_kriteria ?>" class="px-6 py-3 text-center">
                            Kriteria
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-6 py-3 ">
                            Alternatif
                        </th>
                        <?php foreach ($kriteria as $key => $value) : ?>
                            <th scope="col" class="px-6 py-3">
                                C
                                <?= $key + 1 ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternatif as $key => $value) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4">
                                <?= $value['nama_alternatif'] ?>
                            </th>
                            <?php foreach ($nilai_matriks as $kunci => $nilai) : ?>
                                <?php if ($value['id_alternatif'] == $nilai['id_alternatif']) : ?>
                                    <td class="px-6 py-4">
                                        <?= $nilai['nilai_matriks'] ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-5/6 shadow-md bg-white rounded-md pb-5 mx-auto mb-10">
        <h2 class="mx-auto my-5 tracking-widest border-b border-blue-500 text-center">Matriks Keputusan Ternormalisasi
        </h2>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="">
                        <th class=""></th>
                        <th scope="col" colspan="<?= $jumlah_kriteria ?>" class="px-6 py-3 text-center">
                            Kriteria
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-6 py-3 ">
                            Alternatif
                        </th>
                        <?php foreach ($kriteria as $key => $value) : ?>
                            <th scope="col" class="px-6 py-3">
                                C
                                <?= $key + 1 ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternatif as $key => $value) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4">
                                <?= $value['nama_alternatif'] ?>
                            </th>
                            <?php foreach ($nilai_matriks_ternormalisasi as $kunci => $nilai) : ?>
                                <td class="px-6 py-4">
                                    <?= number_format($nilai['nilai_matriks'][$key], 6) ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-blue-50 border-b dark:bg-gray-800 dark:border-gray-700">
                        <th class="px-6 py-4">
                            Bobot
                        </th>
                        <?php foreach ($kriteria as $key => $value) : ?>
                            <td class="px-6 py-4">
                                <?= $value['bobot_kriteria'] / 100 ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-5/6 shadow-md bg-white rounded-md pb-5 mx-auto mb-10">
        <h2 class="mx-auto my-5 tracking-widest border-b border-blue-500 text-center">Matriks Optimalisasi Nilai Atribut
        </h2>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="">
                        <th class=""></th>
                        <th scope="col" colspan="<?= $jumlah_kriteria ?>" class="px-6 py-3 text-center">
                            Kriteria
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-6 py-3 ">
                            Alternatif
                        </th>
                        <?php foreach ($kriteria as $key => $value) : ?>
                            <th scope="col" class="px-6 py-3">
                                C
                                <?= $key + 1 ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternatif as $key => $value) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4">
                                <?= $value['nama_alternatif'] ?>
                            </th>
                            <?php foreach ($nilai_matriks_optimalisasi as $kunci => $nilai) : ?>
                                <td class="px-6 py-4">
                                    <?= number_format($nilai['nilai_matriks'][$key], 6) ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-5/6 shadow-md bg-white rounded-md pb-5 mx-auto mb-10">
        <h2 class="mx-auto my-5 tracking-widest border-b border-blue-500 text-center">Mencari Max dan Min
        </h2>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 ">
                            Alternatif
                        </th>
                        <th scope="col" class="px-6 py-3 ">Max</th>
                        <th scope="col" class="px-6 py-3 ">Min</th>
                        <th scope="col" class="px-6 py-3 ">Max - Min</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternatif as $key => $value) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4">
                                <?= $value['nama_alternatif'] ?>
                            </th>
                            <td class="px-6 py-4">
                                <?= number_format($hasil_jumlah['Benefit'][$key], 6) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= number_format($hasil_jumlah['Cost'][$key], 6) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= number_format($hasil_pengurangan[$key], 6) ?>
                            </td>
                        </tr>
                        <?php
                        $queryHasil = "UPDATE hasil SET nilai_hasil = $hasil_pengurangan[$key] WHERE id_alternatif = $value[id_alternatif]";
                        mysqli_query($conn, $queryHasil);
                        ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-5/6 shadow-md bg-white rounded-md pb-5 mx-auto mb-10">
        <h2 class="mx-auto my-5 tracking-widest border-b border-blue-500 text-center">Perangkingan
        </h2>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 ">
                            Alternatif
                        </th>
                        <th scope="col" class="px-6 py-3 ">Rank</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_hasil = "SELECT hasil.*, alternatif.nama_alternatif FROM hasil INNER JOIN alternatif ON hasil.id_alternatif = alternatif.id_alternatif WHERE hasil.nilai_hasil <> 0  ORDER BY nilai_hasil DESC ";
                    $result_hasil = mysqli_query($conn, $query_hasil);
                    $hasil = [];
                    while ($row = mysqli_fetch_assoc($result_hasil)) {
                        $hasil[] = $row;
                    }
                    ?>
                    <?php foreach ($hasil as $key => $value) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th class="px-6 py-4">
                                <?= $value['nama_alternatif'] ?>
                            </th>
                            <td class="px-6 py-4">
                                <?= $key + 1 ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="./perhitungan_guru.php"><button type="button" class="text-white mx-4 mt-4 w-full bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Kembali</button></a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>