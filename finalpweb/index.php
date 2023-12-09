<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rs";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_pasien          = "";
$id_identitas       = "";
$nama_pasien        = "";
$jenis_kelamin      = "";
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
    $sql  = "delete from pasien where id_pasien= '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='index.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from pasien where id_pasien= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $id_pasien= $r['id_pasien'];
    $id_identitas = $r['id_identitas'];
    $nama_pasien = $r['nama_pasien'];
    $jenis_kelamin = $r['jenis_kelamin'];
    $alamat = $r['alamat'];
    $no_telp = $r['no_telp'];
}

if (isset($_POST['simpan'])) {
    $id_pasien  = $_POST['id_pasien'];
    $id_identitas = $_POST['id_identitas'];
    $nama_pasien      = $_POST['nama_pasien'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat        = $_POST['alamat'];
    $no_telp        = $_POST['no_telp'];

    // Check if ID pasien already exists for updating
    $check_sql = "SELECT * FROM pasien WHERE id_pasien= '$id_pasien'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID pasien exists, proceed with the UPDATE
        $update_sql = "UPDATE pasien SET id_identitas = '$id_identitas', nama_pasien = '$nama_pasien', jenis_kelamin = '$jenis_kelamin', alamat = '$alamat', no_telp = '$no_telp' WHERE id_pasien= '$id_pasien'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data pasien Berhasil Diedit";
        } else {
            $error = "Data pasien Gagal Diedit";
        }
    } else {
        // ID pasien doesn't exist, insert new data
        $insert_sql = "INSERT INTO pasien (id_pasien, id_identitas, nama_pasien, jenis_kelamin, alamat, no_telp) VALUES ('$id_pasien', '$id_identitas', '$nama_pasien', '$jenis_kelamin', '$alamat', '$no_telp')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data pasien Berhasil Disimpan";
        } else {
            $error = "Data pasien Gagal Disimpan";
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
    <title>Data pasien</title>
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
                    header("refresh:5;url=index.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="index.php" method="POST">
                    <div class="mb-3 row">
                        <label for="id_pasien" class="col-sm-2 col-form-label">ID pasien</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_pasien" name="id_pasien" value="<?php echo $id_pasien?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id_identitas" class="col-sm-2 col-form-label">ID Identitas Pasien</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_identitas" name="id_identitas" value="<?php echo $id_identitas ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_pasien" class="col-sm-2 col-form-label">Nama Pasien</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo $nama_pasien ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <input type="Radio" name="jenis_kelamin" value="L" checked> Laki-Laki
                            <input type="Radio" name="jenis_kelamin" value="P" checked> Perempuan
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="no_telp" class="col-sm-2 col-form-label">No. Telepon Pasien</label>
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
                Data pasien
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Pasien</th>
                            <th scope="col">ID Identitas</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No. Telepon Pasien</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM pasien ORDER BY id_pasien ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_pasien  = $r2['id_pasien'];
                            $id_identitas = $r2['id_identitas'];
                            $nama_pasien      = $r2['nama_pasien'];
                            $jenis_kelamin      = $r2['jenis_kelamin'];
                            $alamat        = $r2['alamat'];
                            $no_telp        = $r2['no_telp'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $id_pasien?></td>
                                <td scope="row"><?php echo $id_identitas ?></td>
                                <td scope="row"><?php echo $nama_pasien ?></td>
                                <td scope="row"><?php echo $jenis_kelamin ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $no_telp ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id_pasien?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id_pasien?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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