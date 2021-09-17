<?php

/**
 * Classe Professor é a representação de um professor
 * @author Bryan Ritter
 * @version 1.0
 * @access public 
 */
class Professor {

    /**
     * Representa o codigo do professor
     * @access private
     * @var mixed
     */
    private $codigo;

    /**
     * Representa o nome do professor
     * @access private
     * @var mixed
     */
    private $nome;

    /**
     * Atribui informações ao professor
     * @access private 
     * @param  Int $codigo
     * @param  String $nome
     * @return void
     */
    public function setProfessor($codigo, $nome) {
        $this->codigo = $codigo;
        $this->nome = $nome;
    }

    /**
     * getCodigo
     * @access public
     * @return void
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * getNome
     * @access public
     * @return void
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Salava a instancia
     * @access public
     * @return Boolean true: sucesso; false: falha
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO professor (nome) VALUES (:nome)");
                $stm->execute(array(":nome" => $this->getNome()));
            } else {
                $stm = $db->prepare("UPDATE professor SET nome=:nome WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":codigo" => $this->codigo));
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

    /**
     * A função listar lista todas as instancias da entidade
     * @access public
     * @static 
     * @return Array
     */
    public static function listar() {
        $db = Database::conexao();
        $professores = null;
        $retorno = $db->query("SELECT * FROM professor");
        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);

            $professores[] = $professor;
        }

        return $professores;
    }

    /**
     * Retorno a instancia pelo codigo
     * @access public
     * @static
     * @param  Int $codigo
     * @return Professor professor
     */
    public static function getProfessor($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM professor WHERE codigo= $codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);
            return $professor;
        }
        return false;
    }

    /**
     * Deleta uma instancia da entidade
     * @access public
     * @static
     * @param  Int $codigo codigo da instancia a ser deletada
     * @return Boolean true: sucesso; false: falha
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        if ($db->query("DELETE FROM professor WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }
}
