<?php

function Crawl_Winwin_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // Winwin.ba Settings
    $baseUrl = "http://www.winwin.ba/catalogsearch/result/index/?limit=30&mode=list&q=";
    
    $crawler = $client->request("GET", $baseUrl . $query);    
        
    $pages = $crawler->filter(".pages li > a");
    $errorMsg = count($crawler->filter(".note-msg"));
    
    if(count($pages) == 0)   
    {
        if($errorMsg == 1)
            return;
        else
            $pages = 1;
    }
    else
    {        
        // Got multiple page results, get the number of pages
        $pages = $crawler->filter(".pages li > a.last")->first()->attr("href");
        $pages = intval(getBetween("&p=", "&", $pages));
    }
        
    $numOfTheseProducts = 0;
    for($p = 1; $p <= $pages; $p++)
    {
        if($p > 1)
            $crawler = $client->request("GET", $baseUrl . $query . "&p=" . $p);   
        
        $names = $crawler->filter(".product-name > a")->extract(array("_text", "href"));
        $prices = $crawler->filter(".regular-price > .price")->extract("_text");

        foreach($names as $i => $name)
        {
            $products[] = array(
                    "name" => $name[0], 
                    "price" => priceToFloat($prices[$i]),
                    "link" => $name[1],
                    "site" => "Winwin.ba"
                    );
            
            $numOfTheseProducts++;
            if($numOfTheseProducts == $limitPerVendor) break 2;
        }        
    }
}