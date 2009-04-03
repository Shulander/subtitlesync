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
tempo_inicial = 00:00:00,00
tempo_inicial_correto = 00:00:00,00
tempo_final = 01:00:00,00
tempo_final_correto = 01:00:00,00";

file_put_contents("config_creator.txt", $aFileConfig);

exec("php ".dirname( $argv[0] )."\legenda.php 1 config_creator.txt", $aResultado);
echo "your new subtitle is now ready to use. enjoy it ;)\n";
unlink("config_creator.txt");

?>