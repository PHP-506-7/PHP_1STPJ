<?php

$hour = array_map(function ($i) { return str_pad($i, 2, "0", STR_PAD_LEFT); }, range(0, 23));

$hour = [];
for ($i = 0; $i < 24; $i++) 
{
    $hour[] = str_pad($i, 2, "0", STR_PAD_LEFT);
}

?>