<?php
function googleimage($image){
    ini_set("pcre.jit", "0");
    include "simple_html_dom.php";
    $search_query = $image;
    $search_query = urlencode( $search_query );
    $html = file_get_html( "https://www.google.com/search?q=$search_query&tbm=isch" );
    $images = $html->find('img');
    $image_count = 2; //Enter the amount of images to be shown
    $i = 1;
    foreach($images as $image){
        if($i == $image_count) break;
        return $images[$i];
        $i++;
        // DO with the image whatever you want here (the image element is '$image'):
        
    }
}

    

    ?>