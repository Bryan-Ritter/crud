<?php
session_start();
/**
 * O config é um arquivo que possui as constantes so sistema
 */
include "./config.php";

/**
 * Carregaento da Header do Template
 */
include DIR_TEMPLATE . "header.php";

/**
 * Carregamento automatico de classes
 */
spl_autoload_register(function ($classe) {
    include DIR_HOME . "classes/" . $classe . ".php";
});

/**
 * Separação da URL para ser utilizado em ações posteriores
 * caso ocorra um erro no processo uma página é exibida de acordo
 */
if (isset($_GET["path"])) {
    $path = rtrim($_GET["path"], "/");
    $path = explode("/", $path);

    $pagina = $path[0];

    if (isset($path[1])) {
        $acao = $path[1];
    } else {
        $acao = null;
    }

    if (isset($path[2])) {
        $codigo = $path[2];
    } else {
        $codigo = null;
    }

    if ($pagina == "professores" || $pagina == "turmas" || $pagina == "alunos") {
        include DIR_HOME . $pagina . ".php";
    } else {
        include DIR_HOME . "404.php";
    }
} else {
    include DIR_HOME . "home.php";
}

/**
 * Carregamento final do Template, footer
 */
include DIR_TEMPLATE . "footer.php";
