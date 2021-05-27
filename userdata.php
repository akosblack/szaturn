<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body class="page-color">
<h1 class="logo">Logo here</h1>
<div class="page-header">
    <h1>Üdvözöljük az oldalon kedves <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
<p>
    <a href="userdata.php" class="btn btn-danger selected">Felhasználó</a>
    <a href="tantargy.php" class="btn btn-warning" >Tantárgy</a>
    <a href="hallgato.php" class="btn btn-warning" >Hallgató</a>
</p>
<p>
    <a href="logout.php" class="btn btn-danger">Kilépés</a>
</p>
</div>
<div class="center-welcome">
    <h4 style="text-align: right; color: darkgrey">
        2019/2020 Fekete Ákos N6Z4HP
    </h4>
</div>
</body>
</html>
