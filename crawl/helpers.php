<?php

function priceToFloat($s)
{
    $s = str_replace(",", ".", $s);
    $s = preg_replace("/[^0-9\.]/", "", $s);
    $s = str_replace(".", '',substr($s, 0, -3)) . substr($s, -3);
    return (float) $s;
}

function getBetween($var1="",$var2="",$pool)
{
    $temp1 = strpos($pool,$var1)+strlen($var1);
    $result = substr($pool,$temp1,strlen($pool));
    $dd=strpos($result,$var2);
    if($dd == 0)
    {
        $dd = strlen($result);
    }
    return substr($result,0,$dd);
}