<?php
function readline( $prompt = '' )
{
    echo $prompt;
    return rtrim( fgets( STDIN ), "\n" );
}


echo 'teste: '.str_pad("seilaasdfasdfasdf", 5);
?>