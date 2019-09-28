<?php
include __DIR__ . "/../../vendor/autoload.php";

use \HttpRequest\Main as HttpRequest;

$httpRequest = new HttpRequest;

$httpRequest->isPOST();
$httpRequest->url = "http://zplo.it/request.php";
$httpRequest->postdata = [
    "service" => "api",
    "scope" => implode(",", ["user", "email"])
];
$httpRequest->headers = [
    "api-key: d40d9f0953a23bb6664e9c815ad2b17c9335b789"
];
$httpRequest->execute();

echo "Code : " . $httpRequest->getHttpCode() . PHP_EOL;
echo "Headers : " . $httpRequest->getRealHeaders() . PHP_EOL;
echo "Content Type : " . $httpRequest->getHeaders("Content-Type") . PHP_EOL;
echo "Body : " . $httpRequest->getBody() . PHP_EOL;

?>