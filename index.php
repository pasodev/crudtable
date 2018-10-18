<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title> Upload Excel Expreadsheet</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  <script src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div class="paso-main">
        <div class="paso-section" id="title">
            <h1 class="paso-title">
                Upload Spreadsheet Service
            </h1>
        </div>
        <div class="paso-section" id="upload-section">
            <div id="form-container">
                <form id="excel-upload-form" action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="file"></input>
                    <input type="submit" class="paso-submit" value="Upload"> </input>
                </form>
            </div>
            <?php 
                if (isset($_GET['error'])) {
                ?>
                <div class=paso-error>
                    <p> Error: <?php echo $_GET['error']; ?> </p>
                </div>

            <?php
                }
            ?>
        </div>
    </div>
</body>
</html>
