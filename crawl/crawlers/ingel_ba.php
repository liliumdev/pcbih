<?php

function Crawl_Ingel_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // Ingel.ba Settings
    $baseUrl = "http://ingel.ba/ingelshop/search.php?n=" . $limitPerVendor . "&orderby=position&orderway=desc&search_query=";
    // hack huehuehe, parameter n allows every number, unlike other sites :)
    
    $crawler = $client->request("GET", $baseUrl . $query);    
         
    if(count($crawler->filter(".warning")) == 1 ) 
        return;  
        
    $names = $crawler->filter(".ajax_block_product .center_block h3 > a")->extract(array("title", "href"));
    $prices = $crawler->filter(".right_block > .price")->extract("_text");

    foreach($names as $i => $name)
    {
        // some of their products have the price set at "0" ? wtf?
        $price = priceToFloat($prices[$i]);
        
        if($price > 0)
            $products[] = array(
                    "name" => $name[0], 
                    "price" => $price,
                    "link" => "http://ingel.ba" . $name[1],
                    "site" => "Ingel.ba"
                    );
    }   
}