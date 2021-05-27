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
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $lakcim = mysqli_real_escape_string($link, $_POST['lakcim']);
    $createQuery = sprintf("INSERT INTO tulaj(nev, lakcim) VALUES ('%s', '%s')",
        $nev,
        $lakcim
    );
    mysqli_query($link, $createQuery) or die(mysqli_error($link));
    $created = true;
}
if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $query1 = sprintf("DELETE FROM jarmu WHERE tulajid = '%s'",
        $id);
    $query2 = sprintf("DELETE FROM tulaj WHERE id = '%s'",
        $id);
    mysqli_query($link, $query1) or die(mysqli_error($link));
    mysqli_query($link, $query2) or die(mysqli_error($link));
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
    <h1>Tulajdonosok adatbázis</h1>
<p>
    <a href="userdata.php" class="btn btn-warning">Felhasználó</a>
    <a href="jarmu.php" class="btn btn-warning">Járművek</a>
    <a href="tulaj.php" class="btn btn-danger selected">Tulajdonosok</a>
</p>

<p>
    <a href="logout.php" class="btn btn-danger">Kilépés</a>
</p>
</div>

<div class="container main-content center-page">

    <?php if ($created): ?>
        <p>
            <span class="badge badge-success">Új tulaj felvéve!</span>
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
            <span class="badge badge-success">Tulaj törölve!</span>
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

    <form method="post" action="">
        <div class="card">
            <div class="card-header dark-cell">
                Új tulaj hozzáadása
            </div>
            <div class="card-body">
                <div class="form-group left-text">
                    <label for="nev">Név</label>
                    <input class="form-control" name="nev" id="nev" type="text" />
                </div>
                <div class="form-group left-text">
                    <label for="lakcim">Lakcím</label>
                    <input required class="form-control" name="lakcim" id="lakcim" type="text"  />
                </div>
            </div>
            <div class="card-footer left-text">
                <input class="btn btn-success" name="create" type="submit" value="Létrehozás" />
            </div>
        </div>
    </form>

    <p><h1></h1></p>

    <?php
    $querySelect = "SELECT nev, lakcim, id FROM tulaj";
    if ($search) {
        $querySelect = $querySelect . sprintf(" WHERE LOWER(nev) LIKE '%%%s%%'", mysqli_real_escape_string($link, strtolower($search)));
    }
    $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
    ?>

    <table class="table table-sm table-bordered">
        <thead class="thead-dark">
        <tr>
            <th class="center-text dark-cell">Név</th>
            <th class="center-text dark-cell">Lakcím</th>
            <th class="center-text dark-cell" style="white-space:nowrap;" >ID</th>
            <th class="center-text dark-cell"></th>
        </tr>
        </thead>
        <tbody>

        <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
            <tr>
                <td class="dim-cell"><?=$row['nev']?></td>
                <td class="dim-cell"><?=$row['lakcim']?></td>
                <td class="dim-cell"><?=$row['id']?></td>
                <td class="right-text dim-cell">
                    <form action="tulaj.php" method="post">
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
