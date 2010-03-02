<?php
require_once 'models/conteudo/Pagina.php';
require_once 'models/conteudo/CampoExtraGrupo.php';
/**
 * N�vel mais alto da divis�o dos conteudos, seta os parametros de exibi��o de
 * conteudo da p�gina, esses parametros servem apenas pra especifica se devem
 * ou n�o aparecer determinadas propriedades da p�gina, n�o quer dizer que elas
 * n�o estar�o presentes nas classes da entidade
 *
 * @author Vinicius Fiorio - Samusdev@gmail.com
 * @package conteudos
 * @name super_links
 */
class SuperLink extends Samus_Model {

    /**
     * Nome para identifica��o
     *
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     *  Nome limpo para ser usado como  URL, n�o deve ter espa�o nem caracteres especiais
     * @var string VARCHAR(45)
     */
    protected $cleanNome;

    /**
     * Especifica se este superLink tem ou n�o categorias
     * @var boolean BOOLEAN
     */
    protected $temCategorias;

    /**
     * Especifica se as p�ginas tem fotos
     * @var boolean BOOLEAN
     */
    protected $temFotos;

    /**
     * Especifica se as paginas tem arquvos anexos
     * @var boolean BOOLEAN
     */
    protected $temArquivos;

    /**
     * Especifica se as p�ginas ter�o texto descritivo
     * @var boolean BOOLEAN
     */
    protected $temTexto;

    /**
     * Especifica se as p�ginas tem data
     * @var boolean BOOLEAN
     */
    protected $temData;

    /**
     * Define se o superLink pode ou n�o ser deletado
     * @var boolean BOOLEAN
     */
    protected $fechado;

    /**
     * Item que define a ordem
     * @var string VARCHAR(45)
     */
    protected $itemOrdem;

    /**
     * ASC ou DESC
     * @var string VARCHAR(4)
     */
    protected $ordem;

    /**
     * Especifica se poder�o existir p�ginas que n�o podem ser excluidas
     * @var boolean BOOLEAN
     */
    protected $temFechados;

    /**
     * Especifica se as p�ginas tem tags associadas
     * @var boolean BOOLEAN
     */
    protected $temTag;

    /**
     * Especifica se tem enquetes
     * @var boolean BOOLEAN
     */
    protected $temEnquete;

    /**
     * Especifica se a p�gian tem avalia��es
     * @var boolean BOOLEAN
     */
    protected $temAvaliacao;

    /**
     * Especifica se a p�gina se tem coment�rio
     * @var boolean BOOLEAN
     */
    protected $temComentarios;

    /**
     * Paginas do superLink
     */
    protected $pagina = array();

    /**
     * Define se tem rss
     * @var string BOOLEAN
     */
    protected $temRSS;

    /**
     * Sobre o superlink, descri��o livre e para RSS
     * @var string TEXT
     */
    protected $descricao;
    
    /**
     * Nome do diret�rio onde ser�o esperados os controladores
     * @var string VARCHAR(60)
     */
    protected $diretorio;
    
    /**
     * Define se a p�gina possui Podcast ou n�o podcast
     * @var boolean BOOLEAN
     */
    protected $temPodcast;

    /**
     * Grupos de campos extras
     * @var array
     */
    protected $campoExtraGrupo = array();

    /**
     * Define um superLink para para cria��o de menus e outras funcionalidades
     * @var SuperLink INTEGER
     */
    protected $superLinkPai;
    
    /**
    * Define se a p�gina ser� ou n�o indexada no siteMap
    * @var boolean BOOLEAN
    */
    protected $temSiteMap;

    /**
     * Define se ter� subT�tulo
     * @var boolean BOOLEAN
     */
    protected $temSubTitulo;

    /**
     * Site ao qual este SuperLink pertence
     * @var ConteudoSite INTEGER
     */
    protected $conteudoSite;

    /**
     * Define se a pagina tem op��o de destaques
     * @var boolean BOOLEAN
     */
    protected $temDestaque;
    
    /**
     * Define se o SuperLink funcionar� em todos os sites ConteudoSite
     * @var boolean BOOLEAN
     */
    protected $global;
    

    public function getCampoExtraGrupo() {
        return $this->campoExtraGrupo;
    }

    public function setCampoExtraGrupo($campoExtraGrupo) {
        $this->campoExtraGrupo = $campoExtraGrupo;
    }


    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @return boolean
     */
    public function getTemArquivos() {
        return $this->temArquivos;
    }

    /**
     * @return boolean
     */
    public function getTemCategorias() {
        return $this->temCategorias;
    }



    /**
     * @return boolean
     */
    public function getTemFotos() {
        return $this->temFotos;
    }

    /**
     * @return boolean
     */
    public function getTemTexto() {
        return $this->temTexto;
    }

    /**
     * @param boolean $temArquivos
     */
    public function setTemArquivos($temArquivos) {
        $this->temArquivos = (boolean) $temArquivos;
    }

    /**
     * @param boolean $temCategorias
     */
    public function setTemCategorias($temCategorias) {
        $this->temCategorias = (boolean)  $temCategorias;
    }

    /**
     * @param boolean $temFotos
     */
    public function setTemFotos($temFotos) {
        $this->temFotos = (boolean)  $temFotos;
    }

    /**
     * @param boolean $temTexto
     */
    public function setTemTexto($temTexto) {
        $this->temTexto = (boolean)  $temTexto;
    }

    /**
     * @return boolean
     */
    public function getTemData() {
        return $this->temData;
    }

    /**
     * @param boolean $temData
     */
    public function setTemData($temData) {
        $this->temData = (boolean)  $temData;
    }


    /**
     * @return boolean
     */
    public function getFechado() {
        return $this->fechado;
    }

    /**
     * @param boolean $fechado
     */
    public function setFechado($fechado) {
        $this->fechado = $fechado;
    }

    public function getListOrder() {
        return $this->getItemOrdem() . " " . $this->getOrdem();
    }

    /**
     *
     * @param  string $itemOrdem
     * @return string
     */
    public function setItemOrdem($itemOrdem) {
        $this->itemOrdem = $itemOrdem;
    }

    /**
     * @return string
     */
    public function getItemOrdem() {
        return $this->itemOrdem;
    }

    /**
     *
     * @param string $ordem
     * @return string
     */
    public function setOrdem($ordem) {
        $this->ordem = $ordem;
    }

    /**
     *
     * @return string
     */
    public function getOrdem() {
        return $this->ordem;
    }

    /**
     * @return boolean
     */
    public function getTemFechados() {
        return $this->temFechados;
    }

    /**
     * @param boolean $temFechados
     */
    public function setTemFechados($temFechados) {
        $this->temFechados = (boolean)  $temFechados;
    }

    /**
     * @return array
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @param array $paginas
     */
    public function setPagina($pagina) {
        $this->pagina = $pagina;
    }


    /**
     * @return array
     */
    public function getCampoExtraGrupos() {
        return $this->campoExtraGrupos;
    }

    /**
     * @param array $campoExtraGrupos
     */
    public function setCampoExtraGrupos($campoExtraGrupos) {
        $this->campoExtraGrupos = $campoExtraGrupos;
    }

    /**
     * @return boolean
     */
    public function getTemAvaliacao() {
        return $this->temAvaliacao;
    }

    /**
     * @return boolean
     */
    public function getTemComentarios() {
        return $this->temComentarios;
    }

    /**
     * @return boolean
     */
    public function getTemEnquete() {
        return $this->temEnquete;
    }

    /**
     * @return boolean
     */
    public function getTemTag() {
        return $this->temTag;
    }

    /**
     * @param boolean $temAvaliacao
     */
    public function setTemAvaliacao($temAvaliacao) {
        $this->temAvaliacao = (boolean)  $temAvaliacao;
    }

    /**
     * @param boolean $temComentarios
     */
    public function setTemComentarios($temComentarios) {
        $this->temComentarios = (boolean)  $temComentarios;
    }

    /**
     * @param boolean $temEnquete
     */
    public function setTemEnquete($temEnquete) {
        $this->temEnquete = (boolean)  $temEnquete;
    }

    /**
     * @param boolean $temTag
     */
    public function setTemTag($temTag) {
        $this->temTag = (boolean) $temTag;
    }

    public function getCleanNome() {
        return $this->cleanNome;
    }

    public function setCleanNome($cleanNome) {
        $this->cleanNome = $cleanNome;
    }

    public function getTemRSS() {
        return $this->temRSS;
    }

    public function setTemRSS($temRSS) {
        $this->temRSS = (boolean)  $temRSS;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    
    /**
     * Obtem a descri��o
     * @param $descricao string
     * @return string
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    /**
     *  Obtem o diretorio que deveria ter as p�ginas de exibi��o 
     * @return string
     */
    public function getDiretorio() {
        return $this->diretorio;
    }
    
    /**
     * Define o diretorio
     * @param $diretorio string
     * @return string
     */
    public function setDiretorio($diretorio) {
        $this->diretorio = $diretorio;
    }
    
    /**
     * @return boolean
     */
    public function getTemPodcast() {
        return $this->temPodcast;
    }
    
    /**
     * 
     * @param $temPodcast boolean
     * @return boolean
     */
    public function setTemPodcast($temPodcast) {
        $this->temPodcast = (boolean) $temPodcast;
    }

    /**
     * Obtem um SuperLink
     * @return SuperLink
     */
    public function getSuperLinkPai() {
        return $this->superLinkPai;
    }

    /**
     * Define um superLink pai
     * @param SuperLink $superLinkPai
     */
    public function setSuperLinkPai($superLinkPai) {
        $this->superLinkPai = $superLinkPai;
    }
    
    /**
    * @param $temSiteMap boolean
    */
    public function setTemSiteMap($temSiteMap) {
      $this->temSiteMap = (boolean) $temSiteMap;
    }
    
    /**
     * @return boolean
     */
    public function getTemSiteMap() {
      return $this->temSiteMap;
    }


    public function getTemSubTitulo() {
        return   $this->temSubTitulo;
    }

    /**
     * @param boolean $temSubTitulo
     */
    public function setTemSubTitulo($temSubTitulo) {
        $this->temSubTitulo = (boolean) $temSubTitulo;
    }

    /**
     * @return SuperLinkCO
     */
    public function getCO() {
        require_once 'models/conteudo/SuperLinkCO.php';
        return parent::getCO();
    }


    public function getConteudoSite() {
        return $this->conteudoSite;
    }

    public function setConteudoSite(ConteudoSite $conteudoSite) {
        $this->conteudoSite = $conteudoSite;
    }


    public function getTemDestaque() {
        return $this->temDestaque;
    }

    public function setTemDestaque($temDestaque) {
        $this->temDestaque = (boolean) $temDestaque;
    }



    public function getGlobal() {
        return (boolean) $this->global;
    }

    public function setGlobal($global) {
        $this->global = (boolean) $global;
    }
    
    public function isGlobal() {
        return (boolean) $this->getGlobal();
    }
    
}


?>