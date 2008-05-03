<?php
	abstract class Subtitle {
		protected $config;
		protected $arquivo;
		protected $arquivo_final;
		protected $t_ini;
		protected $t_fim;
		protected $n_ini;
		protected $n_fim;

		function Subtitle($arq) {
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