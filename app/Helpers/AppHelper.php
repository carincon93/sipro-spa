<?php

namespace App\Helpers;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\ClientException;

class AppHelper
{
    protected static $apiUrl = 'https://sena4.sharepoint.com/sites/PlandeaccinMarzo';
    protected static $rootFolder = 'Shared Documents/';

    public static function generateToken()
    {
        $client = new Client();

        try {
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ];
            $options = [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => '75b9ea91-fdd7-448e-bb8a-26cc05d95f0d@cbc2c381-2f2e-4d93-91d1-506c9316ace7',
                    'client_secret' => 'AziPuiT9d5ROyfJijm8gRsz112tl5ZIRK/F/oA8JQkg=',
                    'resource' => '00000003-0000-0ff1-ce00-000000000000/sena4.sharepoint.com@cbc2c381-2f2e-4d93-91d1-506c9316ace7'
                ]
            ];
            $request = new Request('POST', 'https://accounts.accesscontrol.windows.net/cbc2c381-2f2e-4d93-91d1-506c9316ace7/tokens/OAuth/2', $headers);
            $response = $client->sendAsync($request, $options)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['access_token'];
        } catch (ClientException $e) {
            $response = $e->getResponse();

            abort($response->getStatusCode());
        }
    }

    public static function createFolder($folderName)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept' => 'application/json;odata=verbose',
                'Content-Type' => 'application/json;odata=verbose'
            ];
            $body = '{
                "__metadata": {
                    "type": "SP.Folder"
                },
                "ServerRelativeUrl": "' . self::$rootFolder . $folderName . '"
            }';

            $request = new Request('POST', self::$apiUrl . '/_api/web/folders', $headers, $body);
            $response = $client->sendAsync($request)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['d']['ServerRelativeUrl'];
        } catch (ClientException $e) {
            $response = $e->getResponse();

            abort($response->getStatusCode());
        }
    }

    public static function uploadFile($folderName, $file, $fileName)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept' => 'application/json;odata=verbose',
                'Content-Type' => 'application/json;odata=verbose'
            ];
            $options = [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => Utils::tryFopen($file, 'r'),
                        'filename' => $fileName,
                        'headers'  => [
                            'Content-Type' => '<Content-type header>'
                        ]
                    ]
                ]
            ];
            $request = new Request('POST', self::$apiUrl . "/_api/web/GetFolderByServerRelativeUrl('" . self::$rootFolder . $folderName . "')/Files/add(url='" . $fileName . "',overwrite=true)", $headers);
            $response = $client->sendAsync($request, $options)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['d']['ServerRelativeUrl'];
        } catch (ClientException $e) {
            $response = $e->getResponse();

            abort($response->getStatusCode());
        }
    }

    public static function checkFile($file)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept' => 'application/json;odata=verbose',
                'Content-Type' => 'application/json;odata=verbose'
            ];

            $request = new Request('GET', self::$apiUrl . "/_api/web/GetFileByServerRelativeUrl('" . $file . "')/\$value", $headers);
            $response = $client->sendAsync($request)->wait();
            return $response->getStatusCode();
        } catch (ClientException $e) {
            $response = $e->getResponse();

            return $response->getStatusCode();
        }
    }

    public static function deleteFile($file)
    {
        $client = new Client();

        if (static::checkFile($file) == 200) {
            try {
                $headers = [
                    'Authorization' => 'Bearer ' . static::generateToken(),
                    'Accept' => 'application/json;odata=verbose',
                    'Content-Type' => 'application/json;odata=verbose'
                ];

                $request = new Request('DELETE', self::$apiUrl . "/_api/web/GetFileByServerRelativeUrl('" . $file . "')", $headers);
                $response = $client->sendAsync($request)->wait();
                $response = json_decode($response->getBody()->getContents(), true);


                return $response;
            } catch (ClientException $e) {
                $response = $e->getResponse();

                abort($response->getStatusCode());
            }
        }
    }

    public static function downloadFile($folderName, $fileName)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept' => 'application/pdf'
            ];
            $request = new Request('GET', self::$apiUrl . "/_api/web/GetFolderByServerRelativeUrl('" . self::$rootFolder . $folderName . "')/Files('" . $fileName . "')/\$value", $headers);
            $response = $client->sendAsync($request)->wait();

            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            echo $response->getBody();
        } catch (ClientException $e) {
            $response = $e->getResponse();

            abort($response->getStatusCode());
        }
    }
}
