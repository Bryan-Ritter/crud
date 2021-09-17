<?php

/**
 * Classe Turma é a representação de uma turma
 * @author Bryan Ritter
 * @version 1.0
 * @access public 
 */
class Turma {

    /**
     * Representa o codigo da turma
     * @access private
     * @var Int
     */
    private $codigo;

    /**
     * Representa o curso da turma
     * @access private
     * @var String
     */
    private $curso;

    /**
     * Representa o nome da turma
     * @access private
     * @var String
     */
    private $nome;

    /**
     * Representa o professor da turma
     * @access private
     * @var Professor
     * @see Professor
     */
    private $professor;

    /**
     * Atribui informações à turma
     * @access private 
     * @param  Int $codigo
     * @param  String $curso
     * @param  String $nome
     * @param  Professor $professor
     * @return void
     */
    public function setTurma($codigo, $curso, $nome, $professor) {
        $this->codigo = $codigo;
        $this->curso = $curso;
        $this->nome = $nome;
        $this->professor = $professor;
    }

    /**
     * getCodigo
     * @access public
     * @return Int
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * getNome
     * @access public
     * @return String
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * getCurso
     * @access public
     * @return String
     */
    public function getCurso() {
        return $this->curso;
    }

    /**
     * getProfessor
     * @access public
     * @return Professor
     */
    public function getProfessor() {
        return $this->professor;
    }

    /**
     * A função listar lista todas as instancias da entidade
     * @access public
     * @static 
     * @return Array
     */
    public static function listar() {
        $db = Database::conexao();
        $turmas = null;
        $retorno = $db->query("SELECT * FROM turma");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);

            $turmas[] = $turma;
        }

        return $turmas;
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
        if ($db->query("DELETE FROM turma WHERE codigo=$codigo")) {
            return true;
        }
        return false;
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
                $stm = $db->prepare("INSERT INTO turma (nome, curso, professor_codigo) VALUES (:nome,:curso,:professor)");
                $stm->execute(array(":nome" => $this->getNome(), ":curso" => $this->getCurso(), ":professor" => $this->getProfessor()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE turma SET nome=:nome,curso=:curso,professor_codigo=:professor_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":curso" => $this->curso, ":professor_codigo" => $this->professor->getCodigo(), ":codigo" => $this->codigo));
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

    /**
     * Retorno a instancia pelo codigo
     * @access public
     * @static
     * @param  Int $codigo
     * @return Turma turma
     */
    public static function getTurma($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM turma WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);
            return $turma;
        }
        return false;
    }
}
