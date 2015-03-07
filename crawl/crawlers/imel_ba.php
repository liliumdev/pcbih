<?php

function Crawl_Imel_ba(&$client, &$products, $query)
{
    global $limitPerVendor;
    
    // Imel.ba Settings
    $baseUrl = "https://www.shop.imel.ba/trazi.php?sta=";
    
    $crawler = $client->request("GET", $baseUrl . $query);    
         
    //check if there is no results returned
    if(count($crawler->filter(".nemaRezultata")) == 1 ) 
        return;  
    
    //get the data from result set
    $names = $crawler->filter(".artdes .nazivArtikla")->extract("_text");
    $prices = $crawler->filter(".artdes .cijenaArtikla")->each(function ($node, $i)
    {
        //in case there is a promo price, both the old and new price are being shown
        //this checks whether that's the case, if so it takes the second, newer, price
        //potential improvement would be to show both the old and new price in search results
        $numOfPrices = substr_count($node->text(), 'KM');
        if ($numOfPrices != 1){
            return substr($node->text(), strpos($node->text(), 'KM'));
        } else {
        return $node->text();
        }
    });
    $links = $crawler->filter(".artdes .vidiDetalje")->extract("href");

    foreach($names as $i => $name)
    {
        //slash the currency, change the string to float
        $price = priceToFloat($prices[$i]);
        
        $products[] = array(
                "name" => $name, 
                "price" => $price,
                "link" => "https://www.shop.imel.ba/" . $links[$i],
                "site" => "Imel.ba"
                );
    }   
}