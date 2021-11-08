<?php
define("BASE", "http://localhost/blue/simple-uploader");
define("USER", "admin");
define("PASS", "123");

if (empty($_GET['user']) || empty($_GET['password']) || $_GET['user'] != USER || $_GET['password'] != PASS) {
  http_response_code(401);
  die("Access denied");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>UPLOADER</title>

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">

    <link rel="base" href="<?= BASE; ?>"/>

    <link rel="stylesheet" href="src/assets/fontawesome/css/pro.min.css">
    <link rel="stylesheet" href="src/css/style.css">

    <script src="src/cdn/jquery.js"></script>
    <script src="src/js/scripts.js"></script>
  </head>
  <body>
    <ul>
      <?php
      $folders = scandir(__DIR__);

      if (!empty($folders)) {
        $ignore = [".", "..", ".git", "index.php", "src"];
        foreach ($folders as $key => $value) {
          if (!in_array($value, $ignore)) {
            if (is_dir($value)) {
              echo '<li class="j_open_modal" name="'.$value.'">
                <i class="far fa-folder-open"></i>
                <p>'.$value.'</p>
              </li>';
            }
          }
        }
      }
      ?>
    </ul>

    <div class="modal" id="Modal">
      <div class="modal-box">
        <button class="modal-box-close j_close_modal">
          <i class="fas fa-times"></i>
        </button>

        <div class="modal-box-content">
          <form id="UploadForm" class="form j_form" action="" method="post">
            <input type="hidden" name="AjaxFile" value="Upload">
            <input type="hidden" name="AjaxAction" value="up">
            <input type="hidden" name="folder" value="">

            <label>
              <span class="legend">*Imagem</span>
              <input type="file" name="attachment" value="" accept="image/jpeg, image/x-png" required>
            </label>

            <button class="btn" type="submit" name="button">SALVAR</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>