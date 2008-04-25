<?php
/*	function criaTempo($h, $m, $s, $ms) {
		$t = $ms + 1000 * ($s + 60 * ($m + 60 * ($h)));
		return $t;
	}

	function criaStringTime($val, $mostrar=false) {
		$ms = $val % 1000;
		$val = (int)($val/1000);

		$s = $val % 60;
		$val = (int)($val/60);

		$m = $val % 60;
		$h = (int)($val/60);
		if($mostrar) {
			echo "valor inicial: ".$val_ini."\n";
			echo "h: ".$h.", m: ".$m.", s: ".$s.", ms: ".$ms."\n";
		}
		
		$retorno = sprintf("%02d:%02d:%02d,%03d", $h, $m, $s, $ms);
		return $retorno;
	}
	
	global $config;

	function leConfig($arquivo) {
		global $config;
		$arquivo = file($arquivo);
		foreach($arquivo as $key=>$val) {
			$dados = explode ("=", $val);
			$dados[0] = trim($dados[0]);
			$dados[1] = trim($dados[1]);
			if(strpos ($dados[0], "tempo") !== false){
				$n = sscanf ($dados[1], "%d:%d:%d,%d", $h_ini, $m_ini, $s_ini, $ms_ini);
				$t_ini = criaTempo ( $h_ini, $m_ini, $s_ini, $ms_ini);
				$config[$dados[0]] = $t_ini;
			}else{
				$config[$dados[0]] = $dados[1];
			}
		}
		$config['angular'] = ($config['tempo_final_correto'] - $config['tempo_inicial_correto']) / ($config['tempo_final'] - $config['tempo_inicial']);
		$config['linear'] = $config['tempo_final_correto'] - $config['angular'] * $config['tempo_final'];

		print_r($config);
	}
	
	if(count($argv)!=2 || !is_file($argv[1])){
		echo "Erro no uso do sistema\n";
		echo "O mesmo deve ser usado da seguinte forma\n";
		echo "php legenda.php <arquivo_configuracao>\n";
		exit(0);
	}

	leConfig($argv[1]);

	$arquivo = file_get_contents($config['arquivo_entrada']);
	$arq = explode("

", $arquivo);
	$arquivo_final = "";
	foreach($arq as $key=>$val) {
		$val = explode("\n", $val);
		if(count($val)<3) {
			continue;
		}
		$val[0] += $config['contador'];
		$n = sscanf ( $val[1], "%d:%d:%d,%d --> %d:%d:%d,%d", $h_ini, $m_ini, $s_ini, $ms_ini, $h_fim, $m_fim, $s_fim, $ms_fim);
		$t_ini = criaTempo ( $h_ini, $m_ini, $s_ini, $ms_ini);
		$t_fim = criaTempo ( $h_fim, $m_fim, $s_fim, $ms_fim);

		$n_ini = criaStringTime((int)($t_ini*$config['angular'] + $config['linear']));
		$n_fim = criaStringTime((int)($t_fim*$config['angular'] + $config['linear']));
		$val[1] = $n_ini . " --> ".$n_fim;

		foreach($val as $key=>$val2) {
			$arquivo_final .= $val2."\n";
		}
		$arquivo_final .="\n";
	}
	
	file_put_contents ($config['arquivo_saida'], $arquivo_final);
*/
?>

<?php
	require_once("legenda.php");
	class Legenda1 extends Legenda {

		private $val;

		function Legenda1($arq) {
			$this->leConfig($arq);
		}

		public function whoAmI() {
			echo "Eu sou Legenda1\n";
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
				if(strpos ($dados[0], "tempo") !== false){
					$n = sscanf ($dados[1], "%d:%d:%d,%d", $h_ini, $m_ini, $s_ini, $ms_ini);
					$t_ini = $this->criaTempo ( $h_ini, $m_ini, $s_ini, $ms_ini);
					$this->config[$dados[0]] = $t_ini;
				}else{
					$this->config[$dados[0]] = $dados[1];
				}
			}
			$this->config['angular'] = ($this->config['tempo_final_correto'] - $this->config['tempo_inicial_correto']) / ($this->config['tempo_final'] - $this->config['tempo_inicial']);
			$this->config['linear'] = $this->config['tempo_final_correto'] - $this->config['angular'] * $this->config['tempo_final'];

			print_r($this->config);
		}
		
		private function criaTempo($h, $m, $s, $ms) {
			$t = $ms + 1000 * ($s + 60 * ($m + 60 * ($h)));
			return $t;
		}

		protected function criaNovoTempo(){
			$this->n_ini = $this->criaStringTime((int)($this->t_ini*$this->config['angular'] + $this->config['linear']));
			$this->n_fim = $this->criaStringTime((int)($this->t_fim*$this->config['angular'] + $this->config['linear']));
		}

		private function criaStringTime($val, $mostrar=false) {
			$ms = $val % 1000;
			$val = (int)($val/1000);

			$s = $val % 60;
			$val = (int)($val/60);

			$m = $val % 60;
			$h = (int)($val/60);
			if($mostrar) {
				echo "valor inicial: ".$val_ini."\n";
				echo "h: ".$h.", m: ".$m.", s: ".$s.", ms: ".$ms."\n";
			}
			
			$retorno = sprintf("%02d:%02d:%02d,%03d", $h, $m, $s, $ms);
			return $retorno;
		}
		
		protected function abreArquivoOrigem(){
			if(!file_exists ($this->config['arquivo_entrada'])){
				echo "Erro!!!\n";
				echo "o arquivo de entrada não existe\n";
				exit();
			}
			$arquivo = file($this->config['arquivo_entrada']);
			foreach($arquivo as $key=>$val) {
				$arquivo[$key] = trim($val);
			}

			for($i=0, $j=0; $i<count($arquivo); $i++) {
				if(is_numeric($arquivo[$i])){
					while(strlen($arquivo[$i])>0){
						$this->arquivo[$j][] = $arquivo[$i];
						$i++;
					}
					$j++;
				}
			}

			if(strlen($this->arquivo[count($this->arquivo)-1][0])==0) {
				unset($this->arquivo[count($this->arquivo)-1]);
			}
		}

		protected function leDados($val){
			$this->val = $val;
			if(count($this->val)<3) {
				echo "formato errado de legenda\n";
				print_r($this->val);
				exit();
			}
			$this->val[0] += $this->config['contador'];
			$n = sscanf ( $this->val[1], "%d:%d:%d,%d --> %d:%d:%d,%d", $h_ini, $m_ini, $s_ini, $ms_ini, $h_fim, $m_fim, $s_fim, $ms_fim);
			$this->t_ini = $this->criaTempo ( $h_ini, $m_ini, $s_ini, $ms_ini);
			$this->t_fim = $this->criaTempo ( $h_fim, $m_fim, $s_fim, $ms_fim);
		}

		protected function gravaDados(){
			static $i=0;
			$this->val[0] = ++$i;
			$this->val[1] = $this->n_ini . " --> ".$this->n_fim;

			foreach($this->val as $key=>$val2) {
				$this->arquivo_final .= $val2."\n";
			}
			$this->arquivo_final .="\n";
		}
	}
?>