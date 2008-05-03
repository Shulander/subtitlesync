<?php
	require_once("Subtitle.php");
	class Legenda2 extends Subtitle {

		private $str;

		function Legenda2($arq) {
			$this->leConfig($arq);
		}
		
		public function whoAmI() {
			echo "Eu sou Legenda2\n";
		}

		protected function leConfig($arquivo){
			if(!file_exists ($arquivo)){
				echo "Erro!!!\n";
				echo "o arquivo de configuracao não existe\n";
				exit();
			}
			$arquivo = file($arquivo);
			foreach($arquivo as $key=>$val) {
				$dados = explode ("=", $val);
				$dados[0] = trim($dados[0]);
				$dados[1] = trim($dados[1]);
				$this->config[$dados[0]] = $dados[1];
			}
			$this->config['angular'] = ($this->config['tempo_final_correto'] - $this->config['tempo_inicial_correto']) / ($this->config['tempo_final'] - $this->config['tempo_inicial']);
			$this->config['linear'] = $this->config['tempo_final_correto'] - $this->config['angular'] * $this->config['tempo_final'];

			print_r($this->config);
		}

		protected function criaNovoTempo(){
			$this->n_ini = (int) ($this->t_ini*$this->config['angular'] + $this->config['linear']);
			$this->n_fim = (int) ($this->t_fim*$this->config['angular'] + $this->config['linear']);
		}

		protected function abreArquivoOrigem(){
			if(!file_exists ($this->config['arquivo_entrada'])){
				echo "Erro!!!\n";
				echo "o arquivo de entrada não existe\n";
				exit();
			}
			echo $this->arquivo;
			$arquivo = file_get_contents($this->config['arquivo_entrada']);
			$this->arquivo = explode("\n", $arquivo);

			if(strlen($this->arquivo[count($this->arquivo)-1])==0) {
				unset($this->arquivo[count($this->arquivo)-1]);
			}
		}

		protected function leDados($val){
			sscanf ( $val, "{%d}{%d}%s", $this->t_ini, $this->t_fim, $str);
			$val2 = explode("}{".$this->t_fim."}", $val);
			$this->str = $val2[1];
		}

		protected function gravaDados(){
			$this->arquivo_final .= "{".$this->n_ini."}{".$this->n_fim."}".$this->str."\n";
		}
	}
?>