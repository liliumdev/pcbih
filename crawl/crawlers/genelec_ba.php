<?php

function Crawl_Genelec_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // Genelec.ba Settings
    $baseUrl = "http://genelec.ba/search?q=";
    
    $crawler = $client->request("GET", $baseUrl . $query); 
    
    $pages = 1;
    if(count($crawler->filter(".pager .last-page")) > 0)
    {
        $lastPageLink = $crawler->filter(".pager .last-page > a")->attr("href");
        
        $pages = substr($lastPageLink, -1); // won't work for numbers with more than one digit
                                            // but tbh will never be neccessary
    }
                
    $numOfTheseProducts = 0;
    for($p = 1; $p <= $pages; $p++)
    {
        if($p > 1)
            $crawler = $client->request("GET", $baseUrl . $query . "&pagenumber=" . $p);   
        
        $names = $crawler->filter(".product-title > a")->extract(array("_text", "href"));
        $prices = $crawler->filter(".product-price > .price")->extract("_text");

        foreach($names as $i => $name)
        {
            $products[] = array(
                    "name" => $name[0], 
                    "price" => priceToFloat($prices[$i]),
                    "link" => "http://genelec.ba" . $name[1],
                    "site" => "Genelec.ba"
                    );
            
            $numOfTheseProducts++;
            if($numOfTheseProducts == $limitPerVendor) break 2;
        }        
    }
}