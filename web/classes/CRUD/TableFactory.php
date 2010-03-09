<?php
require_once 'CRUD/DAO.php';
require_once 'CRUD/Properties.php';
require_once 'samus/Samus_Object.php';

/**
 * -----------------------------------------------------------------------------
 * ATEN��O! ####################################################################
 * Este m�dulo utiliza os coment�rios para cria��o de c�digo, por isso qualquer
 * extens�o que acelere o php eliminando o Cache do PHP fara com que esta classe
 * n�o funcione corretamente, o mais comum � a extens�o encontrada na linha:
 *
 * zend_extension = "C:\xampp\php\ext\php_eaccelerator.dll"
 * -----------------------------------------------------------------------------
 *
 * Cria as tabelas do padr�o CRUD, os coment�rios dos atributos das classes devem
 * especificar al�m do tipo de dado no php o tipo de dado no banco
 *
 * Ex.:
 * string VARCHAR(125) NOT NULL
 * int INTEGER(10) NOT NULL
 *
 * FKs tamb�m s�o geradas autom�ticamente para cada objeto aninhado na classe
 *
 * People INTEGER(10)
 * private $people;
 *
 * Aten��o: Alguns acertos finais dever�o ser feitos ap�s a cria��o da tabela
 *
 * - adicionada a funcionalidade de cria��o de tabelas com varias chaves primarias
 * bastando informar primary key no coment�rio da propriedade
 *
 * - 27/05/2009 - Adicionada a funcionadade de especifca��o dos parametros das
 * FKs atrav�s das constantes ON_DELETE on ON_UPDATE
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.6.0 25/08/2008
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 */
class TableFactory extends Samus_Object {

/**
 * Define se ser� feita uma avalia��o da classe para verificar a exist�ncia
 * da tabela correspondente
 * @var boolean
 */
    private static $createTables = false;

    /**
     * Nome da tabela da entidade
     * @var string
     */
    private static $dbTable;

    /**
     * Especifica se a tabela da entidade deve ser excluida e criada novamente
     * @var boolean
     */
    private $dropToCreate = false;

    /**
     * Um Objeto para analise
     * @var DAO|string
     */
    private $DAO_Object;

    /**
     * Uma array que faz o controle das Primary Keys para correta cria��o  de
     * chaves candidatas
     * @var array
     */
    private $pkArray = array();

    const PRIMARY_KEY_NAME = "id";

    /**
     * Nome da constante que ter� os parametros ON_DELETE
     */
    const ON_DELETE_CLASS_CONST = "ON_DELETE";

    /**
     * Nome da constante que tem os par�metros do ON_UPDATE
     */
    const ON_UPDATE_CLASS_CONST = "ON_UPDATE";

    /**
     * Se informado o ID do objeto, ser� carregado automaticamente os dados
     * do objeto indicado;
     * @param string|int $id
     */
    public function __construct($DAO_Object) {
        $this->DAO_Object = $DAO_Object;
        $this->analyseClass($DAO_Object);
    }

    /**
     * Sobrescreve a fun��o strstr() do Core do PHP para uso espec�fico na classe
     * retornando corretamente a string depois da string encontrada
     *
     * @param string $haystack
     * @param string $needle
     * @param boolean $before_needle
     * @return string
     */
    private function strstr($haystack, $needle, $before_needle = FALSE) {
        if (($pos = strpos($haystack , $needle)) === FALSE)
            return FALSE;

        if ($before_needle)
            return substr($haystack , 0 , $pos + strlen($needle));
        else
            return substr($haystack , $pos);
    }

    /**
     * Esta classe � respons�vel por analizar a classe filha, a partir desse metodo
     * ele popula os atributos $dbColumns com um array de dados para montar a tabela
     * e monta o nome da tabela
     */
    private function analyseClass($classObject = null) {

        if ($classObject == null) {
            $classObject = $this;
        }

        $ref = new ReflectionClass($classObject);

        //o nome da tabela � o nome da classe com underlines no lugar das maiusculas
        $tableName = $this->buildTableName($ref->getName());

        self::setDbTable($tableName);

        if (self::$createTables) {

            $this->buildTableColumns($classObject);

            try {
                $r = CRUD::executeQuery("SELECT 1 FROM $tableName");
            } catch (CRUDQueryException $ex) {
                $this->createTable();
            }



        //mysqli_query(CRUD::getConn() , "SELECT 1 FROM $tableName");

        }
    }

    /**
     * Especifica as colunas da tabela
     *
     * @param mixed|string $classObject
     */
    private function buildTableColumns($classObject = null) {
        if ($classObject == null)
            $classObject = $this;

        $dbColumns = array();
        $ref = new ReflectionClass($classObject);

        $properties = $ref->getProperties();

        foreach ( $properties as $propriedade ) {
            $doc = $propriedade->getDocComment();

            if (! empty($doc)) {
                $doc = strstr($doc , "@var");
                if (! empty($doc)) {

                    $doc = str_replace("/" , "" , $doc);
                    $doc = str_replace("*" , "" , $doc);
                    $doc = str_replace("@var" , "" , $doc);
                    $doc = trim($doc);

                    // se encontrar alguma chave prim�ria no coment�rio guarda em um array para adicionar depois no momento da cria��o
                    if(strpos(strtolower($doc), "primary key")) {
                        $this->pkArray[] = $propriedade->getName();
                        $doc = str_replace("primary key", "", strtolower($doc));
                    }

                    $parametrosArray = explode(" " , $doc);

                    array_unshift($parametrosArray , $propriedade->getName());

                    if ($propriedade->getName() == self::PRIMARY_KEY_NAME) {
                        array_unshift($dbColumns , $parametrosArray);
                    } else {
                        $dbColumns[] = $parametrosArray;
                    }
                }
            }
        }

        $this->pkArray = array_unique($this->pkArray);

        return $dbColumns;
    }

    /**
     * Cria a tabela no banco conforme os dados da tabela, se vc precisa alterar a estrutura
     * da tabela, ou use um Gerenciador de BD para alter�la (lembre-se de atualizar
     * o PHPDoc)
     *
     */
    public function createTable($topLevelClass = "", $onDelete = "SET NULL", $onUpdate = "CASCADE") {
        $pkStr = "";
        if (empty($topLevelClass)) {
            $topLevelClass = CRUD::getTopLevelClass();
        }

        $sqlToSave = "";

        $ref = new ReflectionClass($this->DAO_Object);

        $parentClassesArray = array(
            $ref);

        while ( $ref->getParentClass()->getName() != $topLevelClass ) {
            $ref = $ref->getParentClass();
            $parentClassesArray[] = $ref;
        }

        $parentClassesArray = array_reverse($parentClassesArray);

        foreach ( $parentClassesArray as $ref ) {

        //caso tenha superClasses


            $superTableName = $this->buildTableName($ref->getName());

            /*******************************************************************
             * GERA��O DE SUPER-TABELAS
             * gera as tabelas das classes pais
             ******************************************************************/
            $sql = "";
            $sql .= "CREATE TABLE IF NOT EXISTS `" . Connection::getDataBaseName() . "`.`" . $superTableName . "` (";

            $fks = "";
            
            foreach ( $this->buildTableColumns($ref->getName()) as $column ) {
                if (count($column) <= 2)
                    continue;

                $sql .= "`" . $column[0] . "` " . $column[2] . " ";

                $cont = 0;
                foreach ( $column as $columnDetail ) {

                    if ($cont > 2) {
                        $sql .= $columnDetail . " ";
                    }
                    ++ $cont;
                }

                if (class_exists($column[1])) {

                    try {

                        $onDeleteConst = $ref->getConstant(self::ON_DELETE_CLASS_CONST);
                        $onUpdateConst = $ref->getConstant(self::ON_UPDATE_CLASS_CONST);

                        if($onDeleteConst) {
                            $onDelete = $onDeleteConst;
                        }

                        if($onUpdateConst) {
                            $onUpdate = $onUpdateConst;
                        }

                    } catch (Exception $ex) {

                    }

                    $fks .= ", CONSTRAINT `fk_" . $ref->getName() . "_" . $column[0] . "`
					    FOREIGN KEY (`" . $column[0] . "` )
					    REFERENCES `" . Connection::getDataBaseName() . "`.`" . $this->buildTableName($column[1]) . "` (`id` )
					    ON DELETE $onDelete
					    ON UPDATE $onUpdate";
                }

                $sql .= " ,
";

            }

            // varre o array de chaves prim�rias para criar abaixo
            if(!empty($this->pkArray)) {

                $pkStr = ",";
                foreach($this->pkArray as $pk) {
                    $pkStr .= "`$pk`, ";
                }
                $pkStr = substr($pkStr, 0, -2);
            }

            $sql .= " PRIMARY KEY  (`" . self::PRIMARY_KEY_NAME . "`$pkStr)";

            if (! empty($fks)) {
                $sql .= $fks;
            }

            if ($ref->getParentClass() && $ref->getParentClass()->getName() != $topLevelClass) {
            //caso tenha uma classe pai faz as FK


                $sql .= "
				  , CONSTRAINT `fk_" . $ref->getParentClass()->getName() . "_" . $ref->getName() . "`
				    FOREIGN KEY (`id` )
				    REFERENCES `" . Connection::getDataBaseName() . "`.`" . $this->buildTableName($ref->getParentClass()->getName()) . "` (`id` )
				    ON DELETE CASCADE
				    ON UPDATE CASCADE
			";

            }

            $sql .= ") ENGINE=" . Connection::getEngine() . " DEFAULT CHARSET=" . Connection::getCharset() . ";";


            //mysqli_query(CRUD::getConn() , $sql);
            
            $r = CRUD::executeQuery($sql);

            if(!$r) {
             echo "<h1>A tabela: ". $this->getDbTable() ." n�o pode ser criada</h1>";
             echo "<code>".$sql."<c/ode>";

            } else {

            echo 'Tabela "' . $superTableName . '" Criada com sucesso ! <br />';

            }
            $sqlToSave .= $sql . "\n\n";

            var_dump($sqlToSave);

        }

        $this->writeCreatesSql($sqlToSave);

    }

    /**
     * Cria um arquivo com todos os SQLs executados
     *
     * @param string $sqlText sql para ser escrita
     * @param string $filename nome do arquivo que ser� salvo
     */
    private function writeCreatesSql($sqlText, $filename = "__creates.sql") {

        $filename = WEB_DIR . $filename;

        if (! $handle = fopen($filename , 'a')) {
            throw new Exception("O arquivo n�o pode ser aberto");
        }

        if (is_writable($filename)) {
            if (fwrite($handle , $sqlText) === FALSE) {
                throw new Exception("N�o foi poss�vel escrever no arquivo ($filename)");
            }

            fclose($handle);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Constroi o nome da classe dentro do padr�o CRUD
     * letras maiusculas)
     *
     * @param string $className nome da tabela
     */
    public function buildTableName($className) {
        return CRUD::$tablePrefix . UtilString::upperToUnderline($className);
    }

    /**
     * Deleta a tabela, usada durante o desenvolvimento caso seja
     * nescess�rio atualizar alguma coluna da tabela
     * @return resource mysqli_resource
     */
    private function dropTable() {
        $sql = "DROP TABLE IF EXISTS `" . Connection::getBanco() . "`.`" . self::getDbTable() . "` ; \n";

        return  CRUD::executeQuery($sql);
    }

    /**
     * @see Persistent::getDbTable()
     * @return string nome da tabela especificada
     */
    public static function getDbTable() {
        return self::$dbTable;
    }

    /**
     *
     *
     * @param string $tableName nome da tabela
     */
    protected static function setDbTable($tableName) {
        self::$dbTable = $tableName;
    }

    protected function dropToCreate() {
        return $this->dropToCreate;
    }

    /**
     * Especifica se a tabela sera Dropada anes de criada
     * @param boolean $dropToCreate
     */
    protected function DropOriginalTableToCreate($dropToCreate) {
        $this->dropToCreate = $dropToCreate;
    }

    /**
     * Habilita os m�todos de cria��o de tabelas, REDUZ O DESEMPENHo
     */
    public static function enableCreateTables() {
        self::$createTables = true;
    }

    /**
     * Disabilita os m�todos de cria��o de tabelas, MELHORA O DESEMPENHo
     */
    public static function disableCreateTables() {
        self::$createTables = false;
    }

    /**
     * Verifica se o modo de cria��o de tabelas esta ativado
     * @return boolean
     */
    public static function isCreateTablesEnabled() {
        return self::$createTables;
    }

}

?>
