<?php

/**
 * Classe Aluno é a representação de um aluno
 * @author Bryan Ritter
 * @version 1.0
 * @access public 
 */
class Aluno {
    /**
     * Representa o codigo do aluno
     * @access private
     * @var Int
     */
    private $codigo;

    /**
     * Representa o nome do aluno
     * @access private
     * @var String
     */
    private $nome;

    /**
     * Representa a matricula do aluno
     * @access private
     * @var Int
     */
    private $matricula;

    /**
     * Representa a turma do aluno
     * @access private
     * @var Turma
     * @see Turma
     */
    private $turma;

    /**
     * Atribui informações ao aluno
     * @access private 
     * @param  Int $codigo
     * @param  String $nome
     * @param  Int $matricula
     * @param  Turma $turma
     * @return void
     */
    public function setAluno($codigo, $nome, $matricula, $turma) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->turma = $turma;
    }

    /**
     * getCodigo
     * @access public
     * @return Int codigo
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * getNome
     * @access public
     * @return String nome
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * getMatricula
     * @access public
     * @return Int matricula
     */
    public function getMatricula() {
        return $this->matricula;
    }

    /**
     * getTurma
     * @access public
     * @return Turma turma
     */
    public function getTurma() {
        return $this->turma;
    }

    /**
     * A função listar lista todas as instancias da entidade
     * @access public
     * @static 
     * @return Array
     */
    public static function listar() {
        $db = Database::conexao();
        $alunos = null;
        $retorno = $db->query("SELECT * FROM aluno");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);

            $alunos[] = $aluno;
        }

        return $alunos;
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
        if ($db->query("DELETE FROM aluno WHERE codigo=$codigo")) {
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
                $stm = $db->prepare("INSERT INTO aluno (nome, matricula, turma_codigo) VALUES (:nome,:matricula,:turma)");
                $stm->execute(array(":nome" => $this->getNome(), ":matricula" => $this->getMatricula(), ":turma" => $this->getTurma()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE aluno SET nome=:nome,matricula=:matricula,turma_codigo=:turma_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":matricula" => $this->matricula, ":turma_codigo" => $this->turma->getCodigo(), ":codigo" => $this->codigo));
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
     * @return Aluno aluno
     */
    public static function getAluno($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM aluno WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);
            return $aluno;
        }
        return false;
    }
}
