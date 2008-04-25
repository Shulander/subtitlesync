<?php
	abstract class Legenda {
		protected $config;
		protected $arquivo;
		protected $arquivo_final;
		protected $t_ini;
		protected $t_fim;
		protected $n_ini;
		protected $n_fim;

		function Legenda($arq) {
		}
		
		public function whoAmI() {
			echo "Eu sou Legenda\n";
		}

		abstract protected function leConfig($arquivo);
		
		abstract protected function criaNovoTempo();
		
		abstract protected function abreArquivoOrigem();

		abstract protected function leDados($val);

		abstract protected function gravaDados();

		private function gravaArquivoDestino() {
			file_put_contents ($this->config['arquivo_saida'], $this->arquivo_final);
		}

		public function executa() {
			$this->abreArquivoOrigem();
			foreach($this->arquivo as $key=>$val) {
				$this->leDados($val);
				$this->criaNovoTempo();
				$this->gravaDados();
			}
			$this->gravaArquivoDestino();
		}
	}
?>
<?php
	require_once("legenda1.php");
	require_once("legenda2.php");

	if(count($argv)!=3 || !is_file($argv[2]) || ($argv[1] != 1 && $argv[1] != 2)){
		echo "Erro no uso do sistema\n";
		echo "O mesmo deve ser usado da seguinte forma\n";
		echo "php legenda.php <tipo_legenda> <arquivo_configuracao>\n";
		echo "tipo 1: 00:00:22,465 --> 00:00:26,866\n";
		echo "tipo 2: {2236}{2343}que se autodenomina 'Zero Cool',\n";
		exit(0);
	}
	if($argv[1] == 1) {
		$legenda = new Legenda1($argv[2]);
	}else{
		$legenda = new Legenda2($argv[2]);
	}

	$legenda->executa();

?>