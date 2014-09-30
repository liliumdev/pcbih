<?php

function Crawl_Plus_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // Plus.ba Settings
    $perPage = 6;
    $baseUrl = "http://www.plus.ba/pretraga/";
    
    $crawler = $client->request("GET", $baseUrl . $query . "/0");    
    
    $pages = count($crawler->filter(".pagination li > a")) - 1;
    if($pages == -1) $pages = 0;
    
    $numOfTheseProducts = 0;
    for($p = 0; $p <= $pages*$perPage; $p += $perPage)
    {
        if($p > 0)
            $crawler = $client->request("GET", $baseUrl . $query . "/" . $p);   
        
        $names = $crawler->filter(".searchtbl tr:not(:first-child) h2 > a")->extract(array("_text", "href"));
        $prices = $crawler->filter(".searchtbl tr:not(:first-child) .mainprice")->extract("_text");

        foreach($names as $i => $name)
        {
            $products[] = array(
                    "name" => $name[0], 
                    "price" => priceToFloat($prices[$i]),
                    "link" => $name[1],
                    "site" => "Plus.ba"
                    );
            
            $numOfTheseProducts++;
            if($numOfTheseProducts == $limitPerVendor) break 2;
        }        
    }
}