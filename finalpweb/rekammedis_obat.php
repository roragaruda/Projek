<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rs";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_beri_obat        = "";
$id_rm      = "";
$id_obat        = "";
$banyak_obat        = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql  = "delete from rekammedis_obat where id_beri_obat = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    if ($q) {
        echo "<script>alert('Data Berhasil Dihapus'); document.location.href='rekammedis_obat.php';</script>";
    } else {
        echo "<script>alert('Data Gagal Dihapus'); document.location.href='rekammedis_obat.php';</script>";
    }
}

if ($op == 'edit') {
    $id    = $_GET['id']; // Corrected to use 'id' instead of '$id'
    $sql   = "select * from rekammedis_obat where id_beri_obat= '$id'";
    $q     = mysqli_query($koneksi, $sql);
    $r     = mysqli_fetch_array($q);

    $id_beri_obat= $r['id_beri_obat'];
    $id_rm = $r['id_rm'];
    $id_obat = $r['id_obat'];
    $banyak_obat = $r['banyak_obat'];
}

if (isset($_POST['simpan'])) {
    $id_beri_obat  = $_POST['id_beri_obat'];
    $id_rm = $_POST['id_rm'];
    $id_obat      = $_POST['id_obat'];
    $banyak_obat = $_POST['banyak_obat'];

    // Check if ID rekammedis_obat already exists for updating
    $check_sql = "SELECT * FROM rekammedis_obat WHERE id_beri_obat= '$id_beri_obat'";
    $check_result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // ID rekammedis_obat exists, proceed with the UPDATE
        $update_sql = "UPDATE rekammedis_obat SET id_rm = '$id_rm', id_obat = '$id_obat', banyak_obat = '$banyak_obat',  WHERE id_beri_obat= '$id_beri_obat'";
        $update_result = mysqli_query($koneksi, $update_sql);

        if ($update_result) {
            $sukses = "Data Pengambilan Obat Berhasil Diedit";
        } else {
            $error = "Data Pengambilan Obat Gagal Diedit";
        }
    } else {
        // ID rekammedis_obat doesn't exist, insert new data
        $insert_sql = "INSERT INTO rekammedis_obat (id_beri_obat, id_rm, id_obat) VALUES ('$id_beri_obat', '$id_rm', '$id_obat', $banyak_obat')";
        $insert_result = mysqli_query($koneksi, $insert_sql);

        if ($insert_result) {
            $sukses = "Data Pengambilan Obat Berhasil Disimpan";
        } else {
            $error = "Data Pengambilan Obat Gagal Disimpan";
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
    <title>Data rekammedis_obat</title>
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
                    header("refresh:5;url=rekammedis_obat.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=rekammedis_obat.php");
                }
                ?>
                <form action="rekammedis_obat.php" method="POST">
                    <div class="mb-3 row">
                        <label for="id_beri_obat" class="col-sm-2 col-form-label">ID Ambil Obat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_beri_obat" name="id_beri_obat" value="<?php echo $id_beri_obat?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id_rm" class="col-sm-2 col-form-label">No. Rekam Medis</label>
                        <div class="col-sm-10">
                        <select class="form-control" id="id_rm" name="id_rm">
                            <option value="">Pilih Nomor Rekam Medis</option>
                            <?php
                            include "koneksi.php";
                            $query = mysqli_query($koneksi,"SELECT * FROM rekammedis") or die (mysqli_error($koneksi));
                            while($data = mysqli_fetch_array($query)){
                                echo "<option value=$data[id_rm]> $data[id_rm] </option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="id_obat" class="col-sm-2 col-form-label">ID Obat</label>
                        <div class="col-sm-10">
                        <select class="form-control" id="id_obat" name="id_obat">
                            <option value="">Pilih Obat</option>
                            <?php
                            include "koneksi.php";
                            $query = mysqli_query($koneksi,"SELECT * FROM obat") or die (mysqli_error($koneksi));
                            while($data = mysqli_fetch_array($query)){
                                echo "<option value=$data[id_obat]> $data[id_obat] </option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="banyak_obat" class="col-sm-2 col-form-label">Banyak Obat</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="banyak_obat" name="banyak_obat" value="<?php echo $banyak_obat ?>">
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
                Data Pengambilan Obat
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Ambil Obat</th>
                            <th scope="col">No. Rekam Medis</th>
                            <th scope="col">ID Obat</th>
                            <th scope="col">Banyak Obat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "SELECT * FROM rekammedis_obat ORDER BY id_beri_obat ASC"; // ASC untuk urutan ascending, DESC untuk descending
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_beri_obat  = $r2['id_beri_obat'];
                            $id_rm = $r2['id_rm'];
                            $id_obat      = $r2['id_obat'];
                            $banyak_obat     = $r2['banyak_obat'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $id_beri_obat?></td>
                                <td scope="row"><?php echo $id_rm ?></td>
                                <td scope="row"><?php echo $id_obat ?></td>
                                <td scope="row"><?php echo $banyak_obat ?></td>
                                <td scope="row">
                                    <a href="rekammedis_obat.php?op=edit&id=<?php echo $id_beri_obat?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="rekammedis_obat.php?op=delete&id=<?php echo $id_beri_obat?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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