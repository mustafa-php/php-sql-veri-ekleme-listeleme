<?php
session_start();

try {
  $_deneme = new PDO("mysql:host=localhost;dbname=deney", "root", "");
} catch (PDOException $e) {
  print $e->getMessage();
}

if (isset($_POST["giris"])) {
  $_SESSION["kullanici_adi"] = $_POST["kullanici_adi"];
  header("Location:http://localhost/insta");
}

if (isset($_POST["kaydet"])) {
  $kullanici_adi = $_SESSION["kullanici_adi"];
  $baslik = $_POST["baslik"];
  $icerik = $_POST["icerik"];
  $listele = $_deneme->prepare("INSERT INTO  listeleme SET kullanici_adi=:kullanici_adi, baslik=:baslik, icerik=:icerik");
  $listele->execute(array(
    "kullanici_adi" => $kullanici_adi,
    "baslik" => $baslik,
    "icerik" => $icerik
  ));
  header("Location:http://localhost/insta");
}
if (isset($_GET["cikis"])) {
  if (isset($_SESSION["kullanici_adi"])) {
    session_destroy();
    header("Location:http://localhost/insta");
  } else {
    header("Location:http://localhost/insta");
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
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>
  <style>
    body {
      background: #16233a;
      color: #ffffff;
    }

    .form_div {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      min-height: 20rem;
    }

    .form_div form {
      flex: 0 0 auto;
      width: 60vw;
      height: max-content;
      padding: 2rem;
      padding-bottom: 0;
      background: #2d4f71;
      border-radius: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .form_div form input,
    .form_div form textarea {
      background: #1e3e61;
      color: #ffffff;
      border-radius: 1rem;
      border: none;
      outline: none;
      width: 90%;
      font-size: 1.3rem;
      margin: 0.5rem 0;
      padding: 1rem 2rem;
    }

    .form_div form button {
      background: #1e3e61;
      color: white;
      border-radius: 2rem;
      border: none;
      padding: 1rem 3rem;
      margin-bottom: 0.5rem;
      width: max-content;
      font-size: 1.5rem;
    }

    .paylasim_div {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .paylasim_div .paylasim {
      height: max-content;
      width: 60vw;
      background: #1e2d43;
      padding: 1rem;
      margin: 0.5rem 0;
    }

    .pencere {
      position: fixed;
      height: 100vh;
      width: 100vw;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: #16233a;
    }

    .pencere form {
      background: #1e3e61;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      width: 20rem;
    }

    .pencere form input {
      border-radius: 2rem;
      border: none;
      font-size: 1.2rem;
      padding: .5rem 1.5rem;
      outline: none;
      margin: .5rem;
    }

    .pencere form button {
      border: none;
      border-radius: 1rem;
      font-size: 1.2rem;
      padding: .5rem;
      margin: .5rem;
    }

    .cikis{
      position: fixed;
      top: 5%;
      right: 0%;
      transform: translate(-50%,-50%);
      background: #2d4f71;
      border-radius:.5rem ;
      padding: .5rem 2rem;
    }
  </style>

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
    <a href="http://localhost/insta/?&cikis" class="cikis" >Çıkış</a>
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

  <script>
    $(document).ready(function() {
      $("input").attr("autocomplete", "off");
    });
  </script>
</body>

</html>