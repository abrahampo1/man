<?php
ini_set("pcre.jit", "0");

include_once "simple_html_dom.php";
function googleimage($id){
    include("database.php");
    $sql = "SELECT * FROM inventario WHERE id = $id";
    $do = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($do);
    $image = $row["marca"]." ".$row["modelo"];
    $search_query = $image;
    $search_query = urlencode( $search_query );
    $html = file_get_html( "https://www.google.com/search?q=$search_query&tbm=isch" );
    $images = $html->find('img');
    $image_count = 2; //Enter the amount of images to be shown
    $i = 1;
    foreach($images as $image){
        if($i == $image_count) break;
        preg_match( '@src="([^"]+)"@' , $images[$i], $match );
        $final = substr($match[0], 3);
        $dato =  'data-lazysrc' . $final;
    }
    $sql = "UPDATE `inventario` SET `image` = '$dato' WHERE id = $id";
    if(mysqli_query($link, $sql)){
        return $dato;
    }
}
    ?>