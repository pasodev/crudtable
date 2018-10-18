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
        <div class="paso-section" id="title">

<?php
require('./src/XLSXReader.php');
require('./src/datasource.php');
require('./src/db_config.php');


if (isset($_FILES['file'])) { 

    if ( $_FILES['file']['error'] > 0) {
        echo "<div class=\"paso-error\">";
        echo " Error uploading file: code error " . $_FILES['file']['error'];
        echo "</div>";
        exit();
    }
    $excel = new XLSXReader($_FILES['file']['tmp_name']);

    $sheets = $excel->getSheetNames();

    foreach ($sheets as $sheet) {
        $data = $excel->getSheetData($sheet);
        $records = count($data);
        for ($i = 1; $i < $records; $i++) {
            $db = new datasource($dbParams['database'],
                $dbParams['user'],
                $dbParams['password'],
                $dbParams['host']);

            if ($db) {
                if (!$db->insertEdocs( $data[$i] )) {
                    echo "<div class=\"paso-error\">";
                    var_dump($db->getErrors());
                    echo "Error inserting :" . $data[$i][1];
                    echo "<div>";
                }
            } else {
                echo "<div class=\"paso-error\">";
                echo "Error connecting to database, please ensure you have the correct rigths.";
                echo "<div>";
            }
        }
    }


} else {
    echo "<p> No file selected </p>";
}
?>

            <h2 class="paso-title">
               Process finished.
            </h2>
            <p class="paso-options"> <a href="index.php"> Return to upload form </a> </p>
            <p class="paso-options"> <a href="browse.php">Browse papers</a> </p>
        </div>
    </div>
</body>
</html>


