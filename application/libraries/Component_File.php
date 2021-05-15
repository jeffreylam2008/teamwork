<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_File
{
    public function __construct()
	{
        
    }
    public function Encode($file = [])
    {
        return base64_encode( file_get_contents($file['tmp_name']) );
    }

    public function Decode($output_file, $base64_string )
    {
        return file_put_contents($output_file, file_get_contents($base64_string));
    }
    /**
     * Array to CSV
     * Use to generate CSV
     * @param array list of data
     */
    public function Array2CSV(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
    
        fputs( $df, "\xEF\xBB\xBF" );
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        //return ob_get_clean();
    }

    public function DownloadHeaders($filename) 
    {
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8' );
        // disable caching

        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    public function ReadCSVContents($fileinput)
    {
        $response = array();
        try 
        {
            if(!isset($fileinput)) 
            {
                throw new RuntimeException('Invalid parameters.');
            }

            $content = array();
            $row = 0;
            
            if (($handle = fopen($fileinput['tmp_name'], "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c=0; $c < $num; $c++) {
                        $content[$row][] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[$c]);
                    }
                    $row++;
                }
                fclose($handle);
            }
            $response = array(
                "data" => $content,
                "status" => "success",
                "error" => false,
                "message" => "file content"
            );
        }
        catch(exception $e)
        {
            $response = array(
                "data" =>  "",
                "status" => "failure",
                "error" => true,
                "message" => "No File Found!"
            );
        }
        return json_encode($response);
    }
}