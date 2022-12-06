<? include('functions/corefunc.inc.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h1><?= $_SERVER['DOCUMENT_ROOT']; ?></h1>

    $im = new Imagick($_SERVER['DOCUMENT_ROOT'] . '/site.com/images/image.jpg');

    <form action="" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="carimage" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>



    <?
    if (!empty($_POST)) {
        $targetWidth = 800;
        $targetHeight = 600;
        $path = "upload/cars/";
        upload("carimage", $path, $targetWidth, $targetHeight);
    }
    ?>

</body>

</html>