<?php

/**
 * Ações
 */
if (isset($acao)) {
    if ($acao == "salvar") {
        if ($_POST['enviar-turma']) {
            $professor = new Professor();
            $professor->setProfessor($_POST['codigo-professor-turma'], null);

            $turma = new Turma();
            $turma->setTurma(
                $_POST['codigo-turma'],
                $_POST['curso-turma'],
                $_POST['nome-turma'],
                $professor
            );

            if ($turma->salvar()) {
                $msg['msg'] = "Registro Salvo com sucesso!";
                $msg['class'] = "success";
            } else {
                $msg['msg'] = "Falha ao Salvar Registro!";
                $msg['class'] = "danger";
            }

            $_SESSION['msgs'][] = $msg;
            unset($turma);
        }
    } else if ($acao == "excluir") {
        if (isset($codigo)) {
            if (Turma::excluir($codigo)) {
                $msg['msg'] = "Registro excluido com sucesso!";
                $msg['class'] = "success";
            } else {
                $msg['msg'] = "Falha ao excluir Registro!";
                $msg['class'] = "danger";
            }
            $_SESSION['msgs'][] = $msg;
        }
    } else if ($acao == "editar") {
        if (isset($codigo)) {
            $turma = Turma::getTurma($codigo);
        }
    }
}

/**
 * Mensagens
 */
if (isset($_SESSION['msgs'])) {

    foreach ($_SESSION['msgs'] as $msg)
        echo "<div class='all-msgs alert alert-{$msg['class']}'>{$msg['msg']}</div>";

    echo "<script defer> 
            setTimeout(function() {
                document.querySelector('.all-msgs').style='display:none';
            }, 2000);
        </script>";

    unset($_SESSION['msgs']);
}

/**
 * Formulario
 */
if (!isset($turma)) {
    $turma = new Turma();
    $turma->setTurma(null, null, null, new Professor());
}
?>
<div class="container-fluid">
    <h2> Cadastro de turmas</h2>
    <form name="form-turma" method="POST" action="<?php print URL_HOME ?>turmas/salvar">
        <input type="hidden" name="codigo-turma" value="<?php echo $turma->getCodigo() ?>" />
        <div class="input-group mb-2 mb-2">
            <label class="input-group-text" for="inputGroupCurso">Curso</label>
            <select class="form-select" name="curso-turma">
                <option value="<?php echo $turma->getCurso() ?>"><?php echo $turma->getCurso() ?></option>
                <option value="Informática">Informática</option>
                <option value="Eletronica">Eletrônica</option>
                <option value="Eletrotécnica">Eletrotécnica</option>
                <option value="Macânica">Mecânica</option>
            </select>
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text">Nome da Turma:</span>
            <input type="text" class="form-control" id="nome-turma" name="nome-turma" value="<?php echo $turma->getNome() ?>">
        </div>
        <div class="input-group mb-2 mb-2">
            <label class="input-group-text" for="inputGroupProfessor">Professor</label>
            <select class="form-select" name="codigo-professor-turma">
                <option value="<?php echo $turma->getProfessor()->getCodigo()  ?>"><?php echo $turma->getProfessor()->getNome()  ?></option>
                <?php
                $professores = Professor::listar();
                foreach ($professores as $item) {
                    echo "<option value='{$item->getCodigo()}'>{$item->getNome()}</option>";
                }
                ?>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" name="enviar-turma" value="Enviar" />

    </form>
    <hr />
</div>
<?php

/**
 * Tabela
 */
?>
<div class="container-fluid">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Turma</th>
                <th scope="col">Curso</th>
                <th scope="col">Professor</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            #Busca lista de turmas
            $turmas = Turma::listar();
            foreach ($turmas as $turma) {
                echo "
                    <tr>
                        <td>{$turma->getCodigo()}</td>
                        <td>{$turma->getNome()}</td>
                        <td>{$turma->getCurso()}</td>
                        <td>{$turma->getProfessor()->getNome()}</td>
                        <td>
                            <span class='badge rounded-pill bg-primary'>
                                <a href='" . URL_HOME . "turmas/editar/" . $turma->getCodigo() . "' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                            </span>
                            <span class='badge rounded-pill bg-danger'>
                                <a href='" . URL_HOME . "turmas/excluir/" . $turma->getCodigo() . "'style='color:#fff'><i class='bi bi-trash'></i></a>
                            </span>
                        </td>
                    </tr>
                ";
            }
            ?>
        </tbody>
    </table>
</div>