<?php
function readline( $prompt = '' )
{
	echo $prompt;
	return rtrim( fgets( STDIN ), "\n" );
}
?>
<?php

// definicoes das linguagens
$aLinguagens[] = 'pob';
$aLinguagens[] = 'eng';
$aDefaultLang = 'pob';

$aTraducaoLang['pob'] = 'por';
$aTraducaoLang['eng'] = 'eng';

function getDir() {
	$retorno = getcwd();
	if($retorno[count($retorno)-1]!='\\' || $retorno[count($retorno)-1]!='/') {
		$retorno .= '/';
	}
	return $retorno;
}


function criaComandosExecucao($aFilesReadyToCreate) {
	global $aTraducaoLang;
	global $aLinguagens;
	global $aDefaultLang;
	global $gSeries;

	$aRetorno = array();
	if(!is_array($aFilesReadyToCreate) || count($aFilesReadyToCreate)==0) { return $aRetorno; }

	foreach( $aFilesReadyToCreate as  $key=>$aFilename) {
		// comando e arquivo de saida
		if($pos = strrpos($aFilename, "-")) { 
			$newFilename = substr($aFilename, 0, $pos)."."; 
		} else { $newFilename = $aFilename; }

		$pos1 = strrpos($newFilename,"/");
		$pos2 = strrpos($newFilename,"\\");
		$pos=($pos1>$pos2?$pos1:$pos2);
		$basedir = substr($newFilename, 0, $pos+1);
		$newFilename = substr($newFilename,$pos+1, strlen($newFilename));

		if($gSeries) {
			$pattern = '|^(.*)([0-9][0-9])\.(.*)\.(.*)|';
			$replacement = '$1$2.$4';
			$newFilename = preg_replace($pattern, $replacement, $newFilename);
		}

		
		$cmd = '"mkvmerge" -o "'.$basedir."../".$newFilename.'mkv"';
		// faixa padrao de audio
		$cmd .= ' --language 1:eng --default-track 1:yes -a 1 -d 0 -S "'.$aFilename.'avi"';

		// linguagens
		foreach($aLinguagens as $key=>$val) {
			$cmd .= ' --language 0:'.$aTraducaoLang[$val].' '.($aDefaultLang==$val?' --default-track 0:yes':'').' -s 0 -D -A "'.$aFilename.$val.'.srt"';
		}
		$cmd .= ' --track-order 0:0,0:1,1:0,2:0';
		$cmd .= "\npause";
		$aRetorno[] = $cmd;
		echo $cmd."\n\n";
	}
	return $aRetorno;
}

function buscaArquivosCriar($diretorio) {
	global $aLinguagens;

	$aRetorno = array();
	if ($dh = opendir($diretorio)) {
		while (($file = readdir($dh)) !== false) {
			if($file=='.' || $file=='..'){
				
			} else if(($posicao = strpos($file, ".avi")) !== false) {
				// descobre o nome do arquivo sem a extensao
				$aFilename = substr($file, 0, -3);
				// procura as legendas
				$aPronto = true;
				foreach($aLinguagens as $key=>$val) {
					if(!is_file($diretorio.$aFilename.$val.'.srt')) { $aPronto = false; }
				}
				
				// caso tenha todas as legendas salva o nome do arquivo
				if($aPronto) {
					$aRetorno[] = $diretorio.$aFilename;
				}
			} else if(is_dir($diretorio.$file)===true){
				$aRetorno = array_merge($aRetorno, buscaArquivosCriar($diretorio.$file."/"));
			}
		}
		closedir($dh);
	}

	return $aRetorno;
}

if(count($argv)>=2 && is_dir($argv[1])){
	if(!chdir($argv[1])) {
		echo "nao consegui mudar de diretorio";
		exit(0);
	}
}

$gSeries = (count($argv)==3 && $argv[2]=="series");

$diretorio=getDir();

// busca arquivos prontos para criar
$aFilesReadyToCreate = array();

$aFilesReadyToCreate = buscaArquivosCriar($diretorio);

// gera comandos
$aComandos = criaComandosExecucao($aFilesReadyToCreate);

/*
$aAnswer = readline("Do you want to create the mkv file right now (y/n)?");
$aAnswer = trim($aAnswer);
if($aAnswer == 'y' || $aAnswer == 'Y') {
	foreach($aComandos as $key=>$comando) {
		echo $comando."\n\n";
		system("'".$comando."'");
		echo "your new file is ready to use, test it before erase any file ;)\n";
		system("pause");
		echo "ahahaha";
		exit(0);
	}
}
*/
// executa as criacoes

?>