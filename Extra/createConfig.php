<?php
$Name = $_POST['CFG_name'];
$Domain = $_POST['Domain'];
$SecretKey = $_POST['SecretKey'];
$ShareXheaderKey = $_POST['ShareXheaderKey'];
error_reporting(0);

/*
This is the shareX config data that needs to be written to file
Here you can see the values needed such as the keys are appended here
*/

$cfg_string = "{\r\n  \"Version\": \"13.4.0\",\r\n  \"Name\": \"" . $Name . "\",\r\n  \"DestinationType\": \"ImageUploader\",\r\n  \"RequestMethod\": \"POST\",\r\n  \"RequestURL\": \"" . $Domain . "/Upload.php\",\r\n  \"Parameters\": {\r\n    \"secretKey\": \"" . $SecretKey . "\"\r\n  },\r\n  \"Headers\": {\r\n    \"ShareXheaderKey\": \"" . $ShareXheaderKey . "\"\r\n  },\r\n  \"Body\": \"MultipartFormData\",\r\n  \"FileFormName\": \"sharex\",\r\n  \"URL\": \"$response$\"\r\n}";

/*
This simply saves the values to a .sxcu file (used for shareX)
This uses the POST data from index.php for each of the values
*/
$FileSve = "Configs\\" . randomName(12) . ".sxcu";
$myfile = fopen($FileSve, "w") or die("Unable to open file!");
fwrite($myfile, $cfg_string);
fclose($myfile);

/*
This will ask the user to download the newly generated file
As its a single file it should auto download with no prompt
*/
$file_url = $FileSve;
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . $FileSve . "\"");
readfile($file_url);
exit;

/*
This is a very nice broken down random string generator (code help below)
https://stackoverflow.com/questions/4356289/php-random-string-generator
*/
function randomName($length)
{
    $keys = array_merge(range(0, 9) , range('a', 'z'));
    $key = '';
    for ($i = 0;$i < $length;$i++)
    {
        $key .= $keys[mt_rand(0, count($keys) - 1) ];
    }
    return $key;
}

?>
