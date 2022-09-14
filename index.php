<?php
session_start();

try {
  $_deney = new PDO("mysql:host=localhost;dbname=deney", "root", "");
} catch (PDOException $e) {
  print $e->getMessage();
}

if (isset($_POST["giris"])) {
  $_SESSION["kullanici_adi"] = $_POST["kullanici_adi"];
  header("Location:http://localhost");
}

if (isset($_POST["kaydet"])) {
  $kullanici_adi = $_SESSION["kullanici_adi"];
  $baslik = $_POST["baslik"];
  $icerik = $_POST["icerik"];
  $listele = $_deney->prepare("INSERT INTO  listeleme SET kullanici_adi=:kullanici_adi, baslik=:baslik, icerik=:icerik");
  $listele->execute(array(
    "kullanici_adi" => $kullanici_adi,
    "baslik" => $baslik,
    "icerik" => $icerik
  ));
  header("Location:http://localhost");
}
if (isset($_GET["cikis"])) {
  if (isset($_SESSION["kullanici_adi"])) {
    session_destroy();
    header("Location:http://localhost");
  } else {
    header("Location:http://localhost");
  }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Php Veri Ekleme-Çekme</title>
  <link rel="stylesheet" href="index.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
</head>

<body>
  <?php
  if (empty($_SESSION["kullanici_adi"])) {
  ?>
    <div class="pencere">
      <form action="index.php" method="post">
        <input type="text" placeholder="kullanıcı adı..." name="kullanici_adi" />
        <button name="giris" type="submit">Giriş</button>
      </form>
    </div>
  <?php
  } else {
  ?>
    <a href="http://localhost/?&cikis" class="cikis">Çıkış</a>
    <div class="form_div">
      <form action="index.php" method="post">
        <input type="text" name="baslik" placeholder="Başlık..." />
        <textarea name="icerik" placeholder="İçerik..."></textarea>
        <button type="submit" name="kaydet">Kaydet</button>
      </form>
    </div>
    <div class="paylasim_div">
      <?php
      $liste = $_deneme->prepare("SELECT * FROM listeleme order by id desc");
      $liste->execute();

      $listeleniyor = $liste->fetchAll(PDO::FETCH_OBJ);

      foreach ($listeleniyor as $sonuc) {
      ?>
        <div class="paylasim">
          <div class="paylasim_bas"><?php echo $sonuc->kullanici_adi; ?></div>
          <div class="paylasim_govde">
            <h2><?php echo $sonuc->baslik; ?></h2>
            <p>
              <?php echo $sonuc->icerik; ?>
            </p>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php
  }
  ?>
</body>

</html>