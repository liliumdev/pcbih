<?php

function Crawl_Ue_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // ue.ba Settings
    $baseUrl = "http://shop.ue.ba/search.php?searchfor=";
    
    $crawler = $client->request("GET", $baseUrl . $query); 
    
    $pages = count($crawler->filter(".box-paginacija li")) - 2;
    if($pages == 0) $pages = 1;
    
    $numOfTheseProducts = 0;
    for($p = 1; $p <= $pages; $p++)
    {
        if($p > 1)
            $crawler = $client->request("GET", $baseUrl . $query . "&Pg=" . $p);   
        
        $names = $crawler->filter(".products li .descript > h1")->extract("_text");
        $links = $crawler->filter(".products li .descript")->extract("onclick");
        $prices = $crawler->filter(".products li .price > strong")->extract("_text");

        foreach($names as $i => $name)
        {
            $products[] = array(
                    "name" => $name, 
                    "price" => priceToFloat($prices[$i]),
                    "link" => "http://shop.ue.ba/" . getBetween("location.href='", "';", $links[$i]),
                    "site" => "UE.ba"
                    );
            
            $numOfTheseProducts++;
            if($numOfTheseProducts == $limitPerVendor) break 2;
        }        
    }
}