<?php


interface Samus_FilterInterface {
		
	/**
	 * O filtro � executado sempre que a classe Filtro � executada, todas as 
	 * implementa��es devem ser feitas no construtor e em filter
	 */
	public function filter();
	
	
}


?>