<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rs";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_rm        = "";
$id_pasien      = "";
$keluhan        = "";
$id_dokter        = "";
$diagnosa       = "";
$tgl_periksa       = "";
$id_poli       = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql  = "delete from rekammedis where id_rm = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='rekammedis.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='rekammedis.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from rekammedis where id_rm= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $id_rm= $r['id_rm'];
    $id_pasien = $r['id_pasien'];
    $keluhan = $r['keluhan'];
    $id_dokter = $r['id_dokter'];
    $diagnosa = $r['diagnosa'];
    $tgl_periksa = $r['tgl_periksa'];
    $id_poli = $r['poli'];
}

if (isset($_POST['simpan'])) {
    $id_rm  = $_POST['id_rm'];
    $id_pasien = $_POST['id_pasien'];
    $keluhan      = $_POST['keluhan'];
    $id_dokter = $_POST['id_dokter'];
    $diagnosa = $_POST['diagnosa'];
    $tgl_periksa = $_POST['tgl_periksa'];
    $id_poli = $_POST['poli'];

    // Check if ID rekammedis already exists for updating
    $check_sql = "SELECT * FROM rekammedis WHERE id_rm= '$id_rm'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID rekammedis exists, proceed with the UPDATE
        $update_sql = "UPDATE rekammedis SET id_pasien = '$id_pasien', keluhan = '$keluhan', id_dokter = '$id_dokter', diagnosa = '$diagnosa', tgl_periksa = '$tgl_periksa', id_poli = '$id_poli',  WHERE id_rm= '$id_rm'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data rekammedis Berhasil Diedit";
        } else {
            $error = "Data rekammedis Gagal Diedit";
        }
    } else {
        // ID rekammedis doesn't exist, insert new data
        $insert_sql = "INSERT INTO rekammedis (id_rm, id_pasien, keluhan) VALUES ('$id_rm', '$id_pasien', '$keluhan', $id_dokter', '$diagnosa', '$tgl_periksa', '$id_poli')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data Rekam Medis Berhasil Disimpan";
        } else {
            $error = "Data Rekam Medis Gagal Disimpan";
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
    <title>Data rekammedis</title>
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
                    header("refresh:5;url=rekammedis.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=rekammedis.php");
                }
                ?>
                <form action="rekammedis.php" method="POST">
                    <div class="mb-3 row">
                        <label for="id_rm" class="col-sm-2 col-form-label">ID Rekam Medis</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_rm" name="id_rm" value="<?php echo $id_rm?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id_pasien" class="col-sm-2 col-form-label">Nama Pasien</label>
                        <div class="col-sm-10">
                        <select class="form-control" id="id_pasien" name="nama_pasien">
                            <option value="">Pilih Pasien</option>
                            <?php
                            include "koneksi.php";
                            $query = mysqli_query($koneksi,"SELECT * FROM pasien") or die (mysqli_error($koneksi));
                            while($data = mysqli_fetch_array($query)){
                                echo "<option value=$data[id_pasien]> $data[nama_pasien] </option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="keluhan" class="col-sm-2 col-form-label">Keluhan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="keluhan" name="keluhan" value="<?php echo $keluhan ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id_dokter" class="col-sm-2 col-form-label">Nama Dokter</label>
                        <div class="col-sm-10">
                        <select class="form-control" id="id_dokter" name="nama_dokter">
                            <option value="">Pilih Dokter</option>
                            <?php
                            include "koneksi.php";
                            $query = mysqli_query($koneksi,"SELECT * FROM dokter") or die (mysqli_error($koneksi));
                            while($data = mysqli_fetch_array($query)){
                                echo "<option value=$data[id_dokter]> $data[nama_dokter] </option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="diagnosa" class="col-sm-2 col-form-label">Diagnosa</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="diagnosa" name="diagnosa" value="<?php echo $diagnosa ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tgl_periksa" class="col-sm-2 col-form-label">Tanggal Periksa</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tgl_periksa" name="tgl_periksa" value="<?php echo $tgl_periksa ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id_poli" class="col-sm-2 col-form-label">Nama Poliklinik</label>
                        <div class="col-sm-10">
                        <select class="form-control" id="id_poli" name="nama_poli">
                            <option value="">Pilih Poli</option>
                            <?php
                            include "koneksi.php";
                            $query = mysqli_query($koneksi,"SELECT * FROM poli") or die (mysqli_error($koneksi));
                            while($data = mysqli_fetch_array($query)){
                                echo "<option value=$data[id_poli]> $data[nama_poli] </option>";
                            }
                            ?>
                            </select>
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
                Data rekammedis
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Rekam Medis</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">Keluhan</th>
                            <th scope="col">Nama Dokter</th>
                            <th scope="col">Diagnosa</th>
                            <th scope="col">Tanggal Periksa</th>
                            <th scope="col">Nama Poliklinik</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM rekammedis ORDER BY id_rm ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_rm  = $r2['id_rm'];
                            $id_pasien = $r2['id_pasien'];
                            $keluhan      = $r2['keluhan'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $id_rm?></td>
                                <td scope="row"><?php echo $id_pasien ?></td>
                                <td scope="row"><?php echo $keluhan ?></td>
                                <td scope="row"><?php echo $id_dokter ?></td>
                                <td scope="row"><?php echo $diagnosa ?></td>
                                <td scope="row"><?php echo $tgl_periksa ?></td>
                                <td scope="row"><?php echo $id_poli ?></td>
                                <td scope="row">
                                    <a href="rekammedis.php?op=edit&id=<?php echo $id_rm?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="rekammedis.php?op=delete&id=<?php echo $id_rm?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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