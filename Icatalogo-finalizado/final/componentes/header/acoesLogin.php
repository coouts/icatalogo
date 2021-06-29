<?php
session_start();
require("../../database/conexaoBD.php");

function validarCampos(){
    $erros= [];

    if (!isset($_POST["usuario"]) || $_POST["usuario"] == "") {
        $erros[] = "O campo usuário é obrigatorio";
    }

    if(!isset($_POST["senha"]) || $_POST["senha"] == ""){
        $erros[] = "O campo senha é obrigatorio";
    }

    return $erros;
}

switch ($_POST["acao"]) {
    case 'login':

        $erros = validarCampos();

        if(count($erros) > 0){
            $_SESSION["erros"] = $erros;

            header("location: ../../index.php");
        }

        $usuario = $_POST["usuario"];
        $senha = $_POST["senha"];

        $sql = "SELECT * FROM tbl_administrador where usuario = '$usuario'";
        
        $resultado = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));   
        
        $dadosUsuario = mysqli_fetch_array($resultado);
        
        if (!$dadosUsuario || !password_verify($senha, $dadosUsuario["senha"])) {
    
            $mensagem = "usuário e/ou senha invalidos";
        }else{
            
            $_SESSION["usuarioId"] = $dadosUsuario["id"];
            $_SESSION["ussuarioNome"] = $dadosUsuario["nome"];

            $mensagem = "Bem vindo, " . $dadosUsuario["nome"];
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: ../../produtos/index.php");

        break;
        
    case "logout":
        session_destroy();
        header("location: ../../produtos/index.php");
        break;
    }