<?php

/**
 * Classes para controle e an�lise de desempenho de c�digos
 * 
 * @author Vinicius Fiorio - Samusdev@gmail.com
 * @package util
 * @filesource Performance.php
 *
 */
class Performance {
	
	
	/**
	 * Registra o tempo de execu��o de um c�digo, este m�todo deve ser chamado 
	 * antes do script e depois e exibir� o tempo de execu��o do c�digo
	 *
	 */
	public static function getTime(){
		static $tempo;
		if( $tempo == NULL ){
			$tempo = microtime(true);
		}
		else{
			echo '<hr>Tempo (seg.): '.(microtime(true)-$tempo).'<hr> <br>';
		}
	}
	
	public static function getMemoryUsage() {
	    echo '<hr>'.memory_get_usage().'<hr><br/>';
	}
	
}


?>