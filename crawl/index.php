<?php

require "vendor/autoload.php";
require "helpers.php";

require "crawlers/plus_ba.php";
require "crawlers/winwin_ba.php";
require "crawlers/imtec_ba.php";
require "crawlers/ingel_ba.php";
require "crawlers/ue_ba.php";
require "crawlers/genelec_ba.php";

use GuzzleHttp\Exception\RequestException;
use Goutte\Client;

$limitPerVendor = 5;

if(isset($_GET['vendor']) && isset($_GET['query']))
{
    $query = rawurlencode($_GET['query']);
    $client = new Client();
    $products = array();
    
    $vendor = $_GET['vendor'];
    
    if($vendor == "plus")
        Crawl_Plus_ba($client, $products, $query);
    else if($vendor == "winwin")
        Crawl_Winwin_ba($client, $products, $query);
    else if($vendor == "imtec")
        Crawl_Imtec_ba($client, $products, $query);
    else if($vendor == "ingel")
        Crawl_Ingel_ba($client, $products, $query);
    else if($vendor == "ue")
        Crawl_Ue_ba($client, $products, $query);
    else if($vendor == "genelec")
        Crawl_Genelec_ba($client, $products, $query);
    
    echo json_encode($products);
}
