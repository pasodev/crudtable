<?php
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title> Browse papers </title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/main.js?<?php echo time();?>"></script>
  <script src="js/api.js?<?php echo time();?>"></script>
</head>
<body>
    <div class="paso-main">
        <div class="paso-section" id="title">
            <h1 class="paso-title">
                Browse Papers
            </h1>
        </div>
        <div class="paso-section" id="browse-section">
            <?php require('searchform.php'); ?>
            <table id="paso-paper-table" class="paso-table" start="0">
                <thead>
                    <tr>
                        <td>Year of publication</td>
                        <td> journal</td>
                        <td> Title </td>
                        <td> Authors </td>
                        <td> Actions </td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div class="browse-section">
                <span id="show-previous-ten" class="paso-disabled"> Previous 10 << </span>
                <span id="show-next-ten" class="paso-disabled"> >> Next 10 </span>
            </div>
        </div>
    </div>
    <div class="paso-modal" id="modal-1">
    </div>
</body>
</html>

<script>

$(document).ready(function() {
firstTableLoad();
});

</script>
