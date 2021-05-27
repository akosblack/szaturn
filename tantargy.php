<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<?php
include 'karakter.php';
$link = getDb();
$created = false;
$deleted = false;
if (isset($_POST['create'])) {
    $rendszam = mysqli_real_escape_string($link, $_POST['rendszam']);
    $alvazszam = mysqli_real_escape_string($link, $_POST['alvazszam']);
    $marka = mysqli_real_escape_string($link, $_POST['marka']);
    $szin = mysqli_real_escape_string($link, $_POST['szin']);
    $tulajid = mysqli_real_escape_string($link, $_POST['selectid']);
    $createQuery = sprintf("INSERT INTO jarmu(rendszam, alvazszam, marka, szin, tulajid) VALUES ('%s', '%s', '%s', '%s', '%s')",
        $rendszam,
        $alvazszam,
        $marka,
        $szin,
        $tulajid
    );
    mysqli_query($link, $createQuery) or die(mysqli_error($link));
    $created = true;
}
if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $query = sprintf("DELETE FROM jarmu WHERE id = '%s'",
        $id);
     mysqli_query($link, $query) or die(mysqli_error($link));
    $deleted = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Járművek</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

    <link rel="stylesheet" href="style.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>

<body class="page-color">

<div class="page-header">
    <h1>Járművek adatbázis</h1>
<p>
    <a href="userdata.php" class="btn btn-warning">Felhasználó</a>
    <a href="jarmu.php" class="btn btn-danger selected">Járművek</a>
    <a href="tulaj.php" class="btn btn-warning">Tulajdonosok</a>
</p>
<p>
    <a href="logout.php" class="btn btn-danger">Kilépés</a>
</p>
</div>

<div class="container main-content center-page">

    <?php if ($created): ?>
        <p>
            <span class="badge badge-success">Új jármű felvéve!</span>
        </p>
    <?php endif; ?>

    <?php
    $search = null;
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
    }
    ?>

    <?php if ($deleted): ?>
        <p>
            <span class="badge badge-success">Jármű törölve!</span>
        </p>
    <?php endif; ?>

    <form class="form-inline" method="post">
        <div class="card center-page">
            <div class="card-body bold-text">
                Keresés:
                <input style="width:600px;margin-left:1em;" class="form-control" type="search" name="search" value="<?=$search?>">
                <button class="btn btn-success" style="margin-left:1em;" type="submit" >Search</button>
            </div>
        </div>
    </form>

    <p><h1></h1></p>

    <?php
    $querySelect = "SELECT id, nev FROM tulaj";
    $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
    ?>

    <form method="post" action="">
        <div class="card">
            <div class="card-header dark-cell">
                Új jármű hozzáadása
            </div>
            <div class="card-body">
                <div class="form-group left-text">
                    <label for="rendszam">Rendszám</label>
                    <input class="form-control" name="rendszam" id="rendszam" type="text" />
                </div>
                <div class="form-group left-text">
                    <label for="alvazszam">Alvázszám</label>
                    <input required class="form-control" name="alvazszam" id="alvazszam" type="number"  />
                </div>
                <div class="form-group left-text">
                    <label for="marka">Márka</label>
                    <input class="form-control" name="marka" id="marka" type="text" />
                </div>
                <div class="form-group left-text">
                    <label for="szin">Szín</label>
                    <input class="form-control" name="szin" id="szin" type="text"  />
                </div>

                <div class="form-group left-text">
                    <label for="nev">Név</label>
                    <select class="form-control" name="selectid" size=”3”>
                        <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
                        <option  value="<?=$row['id']?>"><?=$row['nev']?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="card-footer left-text">
                <input class="btn btn-success" name="create" type="submit" value="Létrehozás" />
            </div>
        </div>
    </form>

    <p><h1></h1></p>

    <?php
    $querySelect = "SELECT id, rendszam, alvazszam, marka, szin, tulajid FROM jarmu";
    if ($search) {
        $querySelect = $querySelect . sprintf(" WHERE LOWER(rendszam) LIKE '%%%s%%'", mysqli_real_escape_string($link, strtolower($search)));
    }
    $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
    ?>

    <table class="table table-striped table-sm table-bordered">
        <thead class="thead-dark">
        <tr>
            <th class="center-text dark-cell">Rendszám</th>
            <th class="center-text dark-cell">Alvázszám</th>
            <th class="center-text dark-cell">Márka</th>
            <th class="center-text dark-cell">Szín</th>
            <th class="center-text dark-cell" style="white-space:nowrap;">TulajID</th>
            <th class="center-text dark-cell"></th>
        </tr>
        </thead>
        <tbody>

        <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
            <tr>
                <td class="dim-cell"><?=$row['rendszam']?></td>
                <td class="dim-cell"><?=$row['alvazszam']?></td>
                <td class="dim-cell"><?=$row['marka']?></td>
                <td class="dim-cell"><?=$row['szin']?></td>
                <td class="dim-cell"><?=$row['tulajid']?></td>
                <td class="right-text dim-cell">
                    <form action="jarmu.php" method="post">
                        <input type="hidden" name="id" id="id" value="<?=$row['id']?>" />
                        <input class="btn btn-danger" name="delete" type="submit" value="Törlés" />
                    </form>
                </td>

            </tr>
        <?php endwhile; ?>

        </tbody>
    </table>

    <?php
    closeDb($link);
    ?>

</div>
</body>
</html>
