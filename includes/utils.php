<?php
function estanciaLegenda($theFileName) {
	if (strpos($theFileName, ".srt", 1)>0) {		
		require_once(dirname( __FILE__ )."/../legenda1.php");
		return new Legenda1();
	} else {	
		echo "error: it just support .srt subtitles";
		exit(0);
	}
}

function strFormat($theStr, $theLenght) {
	if (strlen($theStr)>$theLenght) {
		return substr($theStr, 0, $theLenght);
	} else {
		return str_pad($theStr, $theLenght);
	}
}

function readline( $prompt = '' )
{
    echo $prompt;
    return rtrim( fgets( STDIN ), "\n" );
} 

?>