<?php
if(isset($_FILES['picture'])) {
    if(isset($_POST['final-submit'])) {
        $errors = array();
        $file_name = $_FILES['picture']['name'];
        $file_size = $_FILES['picture']['size'];
        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_type = $_FILES['picture']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['picture']['name'])));

        $expensions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be excately 2 MB';
        }

        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "AdImages/" . $file_name);
            echo "Success";
        } else {
            print_r($errors);
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>File upload</title>
</head>

<body>

<form action="#" method="post" enctype="multipart/form-data">
    <input type="file" name="picture" id="picture">
    <input type="submit" name="final-submit" id="final-submit">
</form>

</body>

</html>