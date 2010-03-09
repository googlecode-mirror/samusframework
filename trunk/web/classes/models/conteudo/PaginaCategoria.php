<?php
require_once 'models/conteudo/Categoria.php';
require_once 'models/conteudo/Pagina.php';

/**
 * Classe associativa das paginas com suas categorias,
 * Uma mesma p�gian poder� ser associada a v�rias categorias, clicando em uma
 * categoria deve ser carregada todas as p�ginas que tiverem essa categoria
 * marcada, mesmo repetindo o conte�do de outras p�ginas
 *
 * @name paginas_categorias
 *
 */
class PaginaCategoria extends Samus_Model {

	/**
	 * Categoria
	 *
	 * @var Categoria INTEGER(10)
	 */
	protected $categoria;

	/**
	 * Pagina
	 *
	 * @var Pagina INTEGER(10)
	 */
	protected $pagina;

	public function __construct($id="") {
		parent::__construct($id);
	}

	/**
	 * @return Categoria
	 */
	public function getCategoria() {
		return $this->categoria;
	}

	/**
	 * @return Pagina
	 */
	public function getPagina() {
		return $this->pagina;
	}

	/**
	 * @param Categoria $categoria
	 */
	public function setCategoria(Categoria $categoria) {
		$this->categoria = $categoria;
	}

	/**
	 * @param Pagina $pagina
	 */
	public function setPagina(Pagina $pagina) {
		$this->pagina = $pagina;
	}






}


?>