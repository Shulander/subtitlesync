<?php
require_once(dirname( $argv[0] )."/includes/utils.php");

if(count($argv)!=3 || !is_file($argv[1]) || !is_file($argv[2])){
	echo "[error]\n";
	echo "folow the instruction below\n";
	echo "php create_config.php sincronized_subtitle_file unisicronized_one\n";
	exit(0);
}

$aFile1 = estanciaLegenda($argv[1]);
$aArquivo1 = $aFile1->abreArquivo($argv[1]);

$aFile2 = estanciaLegenda($argv[2]);
$aArquivo2 = $aFile2->abreArquivo($argv[2]);

$nElementos1 = count($aArquivo1);
$nElementos2 = count($aArquivo2);

for($i=0; $i<10; $i++) {
	$aStr1 = ($i+1)." - ".$aArquivo1[$i][2];
	$aStr2 = ($i+1)." - ".$aArquivo2[$i][2];
	echo sprintf("%s |-| %s\n", strFormat($aStr1, 35), strFormat($aStr2, 35));
}

echo str_pad("-=-", 80, "-=-");

do {
	$aEntradaInicio1 = readline("The correct input line (1): ");
	$aEntradaInicio1 += 0;
}while($aEntradaInicio1<1 && $aEntradaInicio1>=10);
$aEntradaInicio1-=1;

do {
	$aEntradaInicio2 = readline("The correct input line (2): ");
	$aEntradaInicio2 += 0;
}while($aEntradaInicio2<1/* && $aEntradaInicio1>=10*/);
$aEntradaInicio2-=1;

for($i=10; $i>0; $i--) {
	$aStr1 = ($nElementos1-$i)." - ".$aArquivo1[$nElementos1-$i][2];
	$aStr2 = ($nElementos2-$i)." - ".$aArquivo2[$nElementos2-$i][2];
	echo sprintf("%s |-| %s\n", strFormat($aStr1, 35), strFormat($aStr2, 35));
}

echo str_pad("-=-", 80, "-=-");

do {
	$aEntradaFinal1 = readline("The correct input line (3): ");
	$aEntradaFinal1 += 0;
}while($aEntradaFinal1<($nElementos1-$i) && $aEntradaFinal1>=$nElementos1);

do {
	$aEntradaFinal2 = readline("The correct input line (4): ");
	$aEntradaFinal2 += 0;
}while(/*$aEntradaFinal2<($nElementos2-$i) && */$aEntradaFinal2>=$nElementos2);

$aFileConfig = "arquivo_entrada = ".$argv[2]."
arquivo_saida = ".$argv[2]."
tempo_inicial = ".substr($aArquivo2[$aEntradaInicio2][1], 0, 12)."
tempo_inicial_correto = ".substr($aArquivo1[$aEntradaInicio1][1], 0, 12)."
tempo_final = ".substr($aArquivo2[$aEntradaFinal2][1], 0, 12)."
tempo_final_correto = ".substr($aArquivo1[$aEntradaFinal1][1], 0, 12);

file_put_contents("config_creator.txt", $aFileConfig);

$aAnswer = readline("Do you want to auto syncronize the subtitle (y/n)?");
$aAnswer = trim($aAnswer);
if($aAnswer == 'y' || $aAnswer == 'Y') {
	exec("php ".dirname( $argv[0] )."\legenda.php 1 config_creator.txt", $aResultado);
	echo "your new subtitle is now ready to use. enjoy it ;)\n";
	unlink("config_creator.txt");
}


//var_dump($aFileConfig);


	/*
	var_dump ($aArquivo1[$i]);
	var_dump ($aFile1->retornaDados( $aArquivo1[$i]));
*/


//var_dump (count($aArquivo));

?>