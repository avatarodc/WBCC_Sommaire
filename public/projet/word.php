<?php
require_once "../../assets/vendor/convertapi-library-php-master/lib/ConvertApi/ConvertApi.php";
require_once "../../assets/vendor/convertapi-library-php-master/lib/ConvertApi/autoload.php";
use \ConvertApi\ConvertApi;

class Word
{
    function getWord($inputFile, $outputFile)
    {
        // Code snippet is using the ConvertAPI PHP Client: https://github.com/ConvertAPI/convertapi-php

            ConvertApi::setApiCredentials('token_fRg3jZcL');
            $result = ConvertApi::convert('docx', [
                    'File' => $inputFile,
                ], 'pdf'
            );
            $result->saveFiles($outputFile);
    }
}
?>