<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rs";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_poli          = "";
$nama_poli       = "";
$ruangan        = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql  = "delete from poli where id_poli= '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='poli.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='poli.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from poli where id_poli= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $id_poli= $r['id_poli'];
    $nama_poli = $r['nama_poli'];
    $ruangan = $r['ruangan'];
}

if (isset($_POST['simpan'])) {
    $id_poli  = $_POST['id_poli'];
    $nama_poli = $_POST['nama_poli'];
    $ruangan      = $_POST['ruangan'];

    // Check if ID poli already exists for updating
    $check_sql = "SELECT * FROM poli WHERE id_poli= '$id_poli'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID poli exists, proceed with the UPDATE
        $update_sql = "UPDATE poli SET nama_poli = '$nama_poli', ruangan = '$ruangan' WHERE id_poli= '$id_poli'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data poli Berhasil Diedit";
        } else {
            $error = "Data poli Gagal Diedit";
        }
    } else {
        // ID poli doesn't exist, insert new data
        $insert_sql = "INSERT INTO poli (id_poli, nama_poli, ruangan) VALUES ('$id_poli', '$nama_poli', '$ruangan')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data Poliklinik Berhasil Disimpan";
        } else {
            $error = "Data Poliklinik Gagal Disimpan";
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
    <title>Data poli</title>
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
                    header("refresh:5;url=poli.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=poli.php");
                }
                ?>
                <form action="poli.php" method="POST">
                    <div class="mb-3 row">
                        <label for="id_poli" class="col-sm-2 col-form-label">ID Poliklinik</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_poli" name="id_poli" value="<?php echo $id_poli?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_poli" class="col-sm-2 col-form-label">Nama Poliklinik</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_poli" name="nama_poli" value="<?php echo $nama_poli ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ruangan" class="col-sm-2 col-form-label">Ruangan Poliklinik</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ruangan" name="ruangan" value="<?php echo $ruangan ?>">
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
                Data poli
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Poliklinik</th>
                            <th scope="col">Nama Poliklinik</th>
                            <th scope="col">Ruangan Poliklinik</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM poli ORDER BY id_poli ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_poli  = $r2['id_poli'];
                            $nama_poli = $r2['nama_poli'];
                            $ruangan      = $r2['ruangan'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $id_poli?></td>
                                <td scope="row"><?php echo $nama_poli ?></td>
                                <td scope="row"><?php echo $ruangan ?></td>
                                <td scope="row">
                                    <a href="poli.php?op=edit&id=<?php echo $id_poli?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="poli.php?op=delete&id=<?php echo $id_poli?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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