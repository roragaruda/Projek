<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rs";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_dokter          = "";
$nama_dokter       = "";
$spesialis        = "";
$alamat             = "";
$no_telp            = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql  = "delete from dokter where id_dokter= '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='dokter.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='dokter.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from dokter where id_dokter= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $id_dokter= $r['id_dokter'];
    $nama_dokter = $r['nama_dokter'];
    $spesialis = $r['spesialis'];
    $alamat = $r['alamat'];
    $no_telp = $r['no_telp'];
}

if (isset($_POST['simpan'])) {
    $id_dokter  = $_POST['id_dokter'];
    $nama_dokter = $_POST['nama_dokter'];
    $spesialis      = $_POST['spesialis'];
    $alamat        = $_POST['alamat'];
    $no_telp        = $_POST['no_telp'];

    // Check if ID dokter already exists for updating
    $check_sql = "SELECT * FROM dokter WHERE id_dokter= '$id_dokter'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID dokter exists, proceed with the UPDATE
        $update_sql = "UPDATE dokter SET nama_dokter = '$nama_dokter', spesialis = '$spesialis', alamat = '$alamat', no_telp = '$no_telp' WHERE id_dokter= '$id_dokter'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data dokter Berhasil Diedit";
        } else {
            $error = "Data dokter Gagal Diedit";
        }
    } else {
        // ID dokter doesn't exist, insert new data
        $insert_sql = "INSERT INTO dokter (id_dokter, nama_dokter, spesialis, alamat, no_telp) VALUES ('$id_dokter', '$nama_dokter', '$spesialis', '$alamat', '$no_telp')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data dokter Berhasil Disimpan";
        } else {
            $error = "Data dokter Gagal Disimpan";
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
    <title>Data dokter</title>
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
                    header("refresh:5;url=dokter.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=dokter.php");
                }
                ?>
                <form action="dokter.php" method="POST">
                    <div class="mb-3 row">
                        <label for="id_dokter" class="col-sm-2 col-form-label">ID Dokter</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_dokter" name="id_dokter" value="<?php echo $id_dokter?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_dokter" class="col-sm-2 col-form-label">Nama Dokter</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="<?php echo $nama_dokter ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="spesialis" class="col-sm-2 col-form-label">Spesialis</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="spesialis" name="spesialis" value="<?php echo $spesialis ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="no_telp" class="col-sm-2 col-form-label">No. Telepon Dokter</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $no_telp ?>">
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
                Data dokter
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Dokter</th>
                            <th scope="col">Nama Dokter</th>
                            <th scope="col">Spesialis</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No. Telepon Dokter</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM dokter ORDER BY id_dokter ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_dokter  = $r2['id_dokter'];
                            $nama_dokter = $r2['nama_dokter'];
                            $spesialis      = $r2['spesialis'];
                            $alamat        = $r2['alamat'];
                            $no_telp        = $r2['no_telp'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $id_dokter?></td>
                                <td scope="row"><?php echo $nama_dokter ?></td>
                                <td scope="row"><?php echo $spesialis ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $no_telp ?></td>
                                <td scope="row">
                                    <a href="dokter.php?op=edit&id=<?php echo $id_dokter?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="dokter.php?op=delete&id=<?php echo $id_dokter?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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