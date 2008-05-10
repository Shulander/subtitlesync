<?php
require_once(dirname( $argv[0] )."/includes/utils.php");

if(count($argv)!=2 || !is_file($argv[1])){
	echo "[error]\n";
	echo "folow the instruction below\n";
	echo "php pass_through.php subtitle_file\n";
	exit(0);
}

$aFileConfig = "arquivo_entrada = ".$argv[1]."
arquivo_saida = ".$argv[1]."
tempo_inicial = 00:01:10,465
tempo_inicial_correto = 00:01:10,465
tempo_final = 00:02:10,465
tempo_final_correto = 00:02:10,465";

file_put_contents("config_creator.txt", $aFileConfig);

$aAnswer = readline("Do you want to auto syncronize the subtitle (y/n)?");
$aAnswer = trim($aAnswer);
if($aAnswer == 'y' || $aAnswer == 'Y') {
	exec("php ".dirname( $argv[0] )."\legenda.php 1 config_creator.txt", $aResultado);
	echo "your new subtitle is now ready to use. enjoy it ;)\n";
	unlink("config_creator.txt");
}

?>