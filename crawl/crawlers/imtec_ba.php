<?php

function Crawl_Imtec_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // Imtec.ba Settings
    $baseUrl = "http://shop.imtec.ba/search?search_query=&controller=search&orderby=position&orderway=desc&search_query=";
    
    $crawler = $client->request("GET", $baseUrl . $query);    
         
    if(count($crawler->filter(".warning")) == 1 ) 
        return;         
    
    // Got results, get the number of pages
    $pages = $crawler->filter(".pagination li:not(#pagination_next) > a");
    if(count($pages) > 0)
        $pages = intval($pages->last()->text());
    else 
        $pages = 1;
    
    $numOfTheseProducts = 0;
    for($p = 1; $p <= $pages; $p++)
    {
        if($p > 1)
            $crawler = $client->request("GET", $baseUrl . $query . "&p=" . $p);   
        
        $names = $crawler->filter(".ajax_block_product .center_block h3 > a")->extract(array("title", "href"));
        $prices = $crawler->filter(".content_price > .price")->extract("_text");

        foreach($names as $i => $name)
        {
            $products[] = array(
                    "name" => $name[0], 
                    "price" => priceToFloat($prices[$i]),
                    "link" => $name[1],
                    "site" => "Imtec.ba"
                    );
            
            $numOfTheseProducts++;
            if($numOfTheseProducts == $limitPerVendor) break 2;
        }        
    }
}