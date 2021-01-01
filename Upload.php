<?php
$request_headers = getallheaders(); // Simply gets all headers sent with request
$method = $_SERVER['REQUEST_METHOD']; // Simply gets the request method (POST , GET) ect
$domain = "AddDomain/IP here (same folder as upload script)"; // Replace this with your domain
$directory = "Uploads/"; // Replace this with the directory where you would like to save images
$secretKey = "ENTERYOURKEYHERE"; // Create a secret key (make this very random)
$SecretKey_check = $_GET['secretKey']; // This checks the key sent from shareX upon request
$randomFileNameLength = 12; // This is the random length allowed for a file name
$ShareXheaderKey = 'ENTERanotherRandomKey'; // This is another key that is checked so make sure this is random
$supportedFiles = array( // this is a very simple array of allowed file upload types (by extension)
    'gif',
    'jpg',
    'jpeg',
    'png',
    'bmp'
);

/*
  This is the main uploading process for the shareX image uploader as you can see below quite alot is happening
  Here it will check for a matching header for the "ShareXheaderKey" and ensure it matches the static one
  Here it will also check the SecretKey url paramater matches the static one set
  Here it will also ensure that the request method is "POST" else it will display and error
*/
if (isset($request_headers['ShareXheaderKey']) && $request_headers['ShareXheaderKey'] == $ShareXheaderKey)
{
    switch ($method)
    {
        case 'POST': // As we only plan to "POST" our images this will check method is POST and nothing else
            if ($SecretKey_check == $secretKey) // This checks if the static key matches the one from shareX (url param)
            
            {
                $randomNameGen = randomName($randomFileNameLength); // This is a random number for the file name
                $target_file = $_FILES["sharex"]["name"]; // This will get the file name / extension uploaded via shareX
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION); // This will grab file extension aka .png
                $fileNameUpload = $randomNameGen . "." . $fileType; // This is final filename to upload (random string + extension)
                $filecheck = strtolower(pathinfo("." . $fileType, PATHINFO_EXTENSION)); // this simplty concatenates the prefix "." +  filename
                if (in_array($filecheck, $supportedFiles))
                { // This simply checks if the extension is in the supportedFiles array
                    if (move_uploaded_file($_FILES["sharex"]["tmp_name"], $directory . $fileNameUpload)) // If filetype is allowed here it will be uploaded
                    
                    {
                        echo $domain . $directory . $fileNameUpload;
                    }
                    else
                    {
                        echo 'File upload failed please ensure domain / directory exists !';
                        echo 'If domain / folders exist make sure it has permissions (777)';
                    }
                }
                else
                {
                    http_response_code(405);
                    header('content-type: text/html;');
                    header('content-language:en');
                    error("File uploaded is not allowed !");
                }

            }
            else
            {
                http_response_code(401);
                header('content-type: text/html;');
                header('content-language:en');
                error("Secret key does not match or is missing !");
            }
        break;
        default:
            http_response_code(405);
            header('content-type: text/html;');
            header('content-language:en');
            error("Please ensure request is POST !");
        break;
    }
}
else
{
    http_response_code(406);
    header('content-type: text/html;');
    header('content-language:en');
    error("Error code - we don't know sorry :c");
}

/*
This is a very simple error that will display an error code and an image to look at
A single paramater is passed to this function "reason" this is the error message to display
*/
function error($reason)
{
    echo "<h1><center>Error 404 - アニメ not found</center></h1>";
    echo "<h3><center>" . $reason . "</center></h3>";
    $image = 'https://img.pngio.com/view-1450598623558-transparent-background-anime-cute-png-clip-cute-anime-backgrounds-png-920_1731.png';
    $imageData = base64_encode(file_get_contents($image));
    echo '<center><img src="data:image/jpeg;base64,' . $imageData . '"></center>';
}

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
