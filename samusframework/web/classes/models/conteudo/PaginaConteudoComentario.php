<?php

require_once ('samus/Samus_Model.php');

class PaginaConteudoComentario extends Samus_Model {

	/**
	 * Pagina do coment�rio
	 *
	 * @var Pagina INTEGER
	 */
	protected $pagina;
	
	/**
	 * Coment�rio da p�gina
	 *
	 * @var ConteudoComentario INTEGER
	 */
	protected $conteudoComentario;
	
	/**
	 * @return ConteudoComentario
	 */
	public function getConteudoComentario() {
		return $this->conteudoComentario;
	}
	
	/**
	 * @return Pagina
	 */
	public function getPagina() {
		return $this->pagina;
	}
	
	/**
	 * @param ConteudoComentario $conteudoComentario
	 */
	public function setConteudoComentario(ConteudoComentario $conteudoComentario) {
		$this->conteudoComentario = $conteudoComentario;
	}
	
	/**
	 * @param Pagina $pagina
	 */
	public function setPagina(Pagina $pagina) {
		$this->pagina = $pagina;
	}
	

	
}

?>