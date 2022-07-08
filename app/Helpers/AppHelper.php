<?php

namespace App\Helpers;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AppHelper
{
    protected static $apiUrl = 'https://sena4.sharepoint.com/sites/gruposennova';
    protected static $rootFolder = 'CONV2023/';

    public static function generateToken()
    {
        $client = new Client();

        try {
            $headers = [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ];
            $options = [
                'form_params' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => '3af81ccc-ebb4-4b5a-9650-dd37d716a8e3@cbc2c381-2f2e-4d93-91d1-506c9316ace7',
                    'client_secret' => 'Qft6c8Lb4wGKhmSZs2CoWvbdUSHMvRNvhYD3fAANXqs=',
                    'resource'      => '00000003-0000-0ff1-ce00-000000000000/sena4.sharepoint.com@cbc2c381-2f2e-4d93-91d1-506c9316ace7'
                ]
            ];
            $request = new Request('POST', 'https://accounts.accesscontrol.windows.net/cbc2c381-2f2e-4d93-91d1-506c9316ace7/tokens/OAuth/2', $headers);
            $response = $client->sendAsync($request, $options)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['access_token'];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function checkFolderAndCreate($folderName)
    {
        $rutas = explode('/', $folderName);
        $formatRoute = '';

        try {
            foreach ($rutas as $ruta) {
                $formatRoute .= $ruta . '/';

                $status = static::checkFolder($formatRoute);

                if ($status == 404) {
                    $folder = static::createFolder($formatRoute);
                    Log::debug($folder);
                }
                Log::debug('Se han creado todas las carpetas');
                return true;
            }
        } catch (\Throwable  $e) {
            Log::debug($e->getMessage());

            abort($e->getStatusCode());
        }
    }

    public static function createFolder($folderName)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept'        => 'application/json;odata=verbose',
                'Content-Type'  => 'application/json;odata=verbose'
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
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function uploadFile($folderName, $file, $fileName)
    {
        $folderNamesFormat = '';
        foreach (explode('/', $folderName) as $value) {
            $folderNamesFormat .= str_replace('+', '%20', urlencode($value)) . '/';
        }
        $urlFormat = str_replace(' ', '%20', self::$rootFolder) . $folderNamesFormat;

        $curl = curl_init();

        try {
            $fileHandler = fopen($file, 'r');
            $fileData = fread($fileHandler, filesize($file));

            $url = self::$apiUrl . '/_api/web/GetFolderByServerRelativeUrl(\'' . $urlFormat . '\')/Files/add(url=\'' . $fileName . '\',overwrite=true)';

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $fileData,
                CURLOPT_INFILE, $fileHandler,
                CURLOPT_INFILESIZE, filesize($file),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . static::generateToken(),
                    'Content-Type: ' . $file->getMimeType(),
                    'Accept: application/json;odata=verbose'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response, true);

            return $response['d']['ServerRelativeUrl'];
        } catch (\Throwable  $e) {
            Log::debug($e->getMessage());

            abort($e->getStatusCode());
        }
    }

    public static function checkFile($file)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept'        => 'application/json;odata=verbose',
                'Content-Type'  => 'application/json;odata=verbose'
            ];

            $request = new Request('GET', self::$apiUrl . "/_api/web/GetFileByServerRelativeUrl('" . $file . "')/\$value", $headers);
            $response = $client->sendAsync($request)->wait();
            return $response->getStatusCode();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            return $response->getStatusCode();
        }
    }

    public static function checkFolder($folderName)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
                'Accept'        => 'application/json;odata=verbose',
                'Content-Type'  => 'application/json;odata=verbose'
            ];

            $request = new Request('GET', self::$apiUrl . "/_api/web/GetFolderByServerRelativeUrl('" . self::$rootFolder . $folderName . "')", $headers);
            $response = $client->sendAsync($request)->wait();
            return $response->getStatusCode();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

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
                    'Accept'        => 'application/json;odata=verbose',
                    'Content-Type'  => 'application/json;odata=verbose'
                ];

                $request = new Request('DELETE', self::$apiUrl . "/_api/web/GetFileByServerRelativeUrl('" . $file . "')", $headers);
                $response = $client->sendAsync($request)->wait();
                $response = json_decode($response->getBody()->getContents(), true);


                return $response;
            } catch (ClientException $e) {
                $response = $e->getResponse();
                Log::debug($e->getMessage());

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
            ];
            $request = new Request('GET', self::$apiUrl . "/_api/web/GetFolderByServerRelativeUrl('" . self::$rootFolder . $folderName . "')/Files('" . $fileName . "')/\$value", $headers);
            $response = $client->sendAsync($request)->wait();

            header('Content-type: force-download');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            echo $response->getBody();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function cleanFileName($nombre, $archivo)
    {
        $cleanName = str_replace(' ', '', substr($nombre, 0, 30));
        $cleanName = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanName);

        $random    = Str::random(10);

        return str_replace(array("\r", "\n"), '', str_replace(array("\r", "\n"), '', "{$cleanName}cod{$random}." . $archivo->extension()));
    }
}
