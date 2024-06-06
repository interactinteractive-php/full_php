<?php
$base64Content = file_get_contents($_FILES['file_1']['tmp_name']);
$fileContent = base64_encode($base64Content);
echo $fileContent; exit;