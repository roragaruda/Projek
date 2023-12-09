<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rs";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_obat          = "";
$nama_obat       = "";
$ket_obat        = "";
$stok             = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql  = "delete from obat where id_obat= '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='obat.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='obat.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from obat where id_obat= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $id_obat= $r['id_obat'];
    $nama_obat = $r['nama_obat'];
    $ket_obat = $r['ket_obat'];
    $stok = $r['stok'];
}

if (isset($_POST['simpan'])) {
    $id_obat  = $_POST['id_obat'];
    $nama_obat = $_POST['nama_obat'];
    $ket_obat      = $_POST['ket_obat'];
    $stok        = $_POST['stok'];

    // Check if ID obat already exists for updating
    $check_sql = "SELECT * FROM obat WHERE id_obat= '$id_obat'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID obat exists, proceed with the UPDATE
        $update_sql = "UPDATE obat SET nama_obat = '$nama_obat', ket_obat = '$ket_obat', stok = '$stok' WHERE id_obat= '$id_obat'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data obat Berhasil Diedit";
        } else {
            $error = "Data obat Gagal Diedit";
        }
    } else {
        // ID obat doesn't exist, insert new data
        $insert_sql = "INSERT INTO obat (id_obat, nama_obat, ket_obat, stok) VALUES ('$id_obat', '$nama_obat', '$ket_obat', '$stok')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data obat Berhasil Disimpan";
        } else {
            $error = "Data obat Gagal Disimpan";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 950px
        }

        .card {
            margin-top: 20px
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <a href="index.php"><button type="button" class="btn btn-primary">Pasien</button></a>
        <a href="dokter.php"><button type="button" class="btn btn-primary">Dokter</button></a>
        <a href="poli.php"><button type="button" class="btn btn-primary">Poliklinik</button></a>
        <a href="obat.php"><button type="button" class="btn btn-primary">Obat</button></a>
        <a href="rekammedis.php"><button type="button" class="btn btn-primary">Rekam Medis</button></a>
        <a href="rekammedis_obat.php"><button type="button" class="btn btn-primary">Pengambilan Obat</button></a>

        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=obat.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=obat.php");
                }
                ?>
                <form action="obat.php" method="POST">
                    <div class="mb-3 row">
                        <label for="id_obat" class="col-sm-2 col-form-label">ID Obat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_obat" name="id_obat" value="<?php echo $id_obat?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_obat" class="col-sm-2 col-form-label">Nama Obat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="<?php echo $nama_obat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ket_obat" class="col-sm-2 col-form-label">Keterangan Obat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ket_obat" name="ket_obat" value="<?php echo $ket_obat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="stok" name="stok" value="<?php echo $stok ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data obat
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Obat</th>
                            <th scope="col">Nama Obat</th>
                            <th scope="col">Keterangan Obat</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM obat ORDER BY id_obat ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_obat  = $r2['id_obat'];
                            $nama_obat = $r2['nama_obat'];
                            $ket_obat      = $r2['ket_obat'];
                            $stok        = $r2['stok'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $id_obat?></td>
                                <td scope="row"><?php echo $nama_obat ?></td>
                                <td scope="row"><?php echo $ket_obat ?></td>
                                <td scope="row"><?php echo $stok ?></td>
                                <td scope="row">
                                    <a href="obat.php?op=edit&id=<?php echo $id_obat?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="obat.php?op=delete&id=<?php echo $id_obat?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</body>

</html>