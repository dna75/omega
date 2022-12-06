<? // increase time by 15 minutes steps - definie start and end time
function dropdown($start, $end, $step = 15, $format = 'H:i')
{
    $output = '';
    $times = array();
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    $currentTime = $startTime;
    while ($currentTime <= $endTime) {
        $times[] = date($format, $currentTime);
        $currentTime += $step * 60;
    }
    foreach ($times as $time) {
        $output .= '<option value="' . $time . '">' . $time . '</option>';
    }
    return $output;
}
?>

<? // Shorten function for creating Real Escape String
function db_escape($string)
{
    global $db;
    if ($db) {
        return mysqli_real_escape_string($db, $string);
    } else {
        return addslashes($string);
    }
}
?>

<? // Search Friendly URL - Sanitiza Special Chars in URL -
function seo_friendly_url($string)
{
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);
    return strtolower(trim($string, '-'));
}
?>

<?
function shorten_string($string, $wordsreturned)
/*  Returns the first $wordsreturned out of $string.  If string
contains fewer words than $wordsreturned, the entire string
is returned.
*/

{
    $retval = $string;      //  Just in case of a problem

    $array = explode(" ", $string);
    if (count($array) <= $wordsreturned)
    /*  Already short enough, return the whole thing
*/ {
        $retval = $string;
    } else
    /*  Need to chop of some words
*/ {
        array_splice($array, $wordsreturned);
        $retval = implode(" ", $array) . " ...";
    }
    return $retval;
}
?>

<?
// Google Maps Address -> Coordinates
function getCoordinates($address)
{
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
    $response = file_get_contents($url);
    $json = json_decode($response, true);

    $lat = $json['results'][0]['geometry']['location']['lat'];
    $lng = $json['results'][0]['geometry']['location']['lng'];

    return array($lat, $lng);
}

// sentence teaser
// this function will cut the string by how many words you want
function word_teaser($string, $count)
{
    $original_string = $string;
    $words = explode(' ', $original_string);

    if (count($words) > $count) {
        $words = array_slice($words, 0, $count);
        $string = implode(' ', $words);
    }

    return $string;
}

// sentence reveal teaser
// this function will get the remaining words
function word_teaser_end($string, $count)
{
    $words = explode(' ', $string);
    $words = array_slice($words, $count);
    $string = implode(' ', $words);
    return $string;
}

// Show first number of chars
function shorten($string, $maxLength)
{
    return substr($string, 0, $maxLength);
}

// upload image and resize image magick
function upload($file, $path, $targetWidth, $targetHeight)
{
    $target_dir = $path;
    // $target_file = $target_dir . basename($_FILES[$file]["name"]);
    $target_file = $target_dir . date('dmYHis') . str_replace(" ", "", basename($_FILES[$file]["name"]));

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$file]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$file]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES[$file]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // resize image
    $resize = new Imagick($target_file);
    // $resize->cropImage(390, 100, 0, 0);
    // $resize->resizeImage(900, 300, Imagick::FILTER_LANCZOS, 1);
    $resize->scaleImage($targetWidth, $targetHeight);
    $resize->writeImage($target_file);
}

?>