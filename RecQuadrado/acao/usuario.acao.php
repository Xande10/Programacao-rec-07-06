<?php
    include_once "../conf/default.inc.php";
    require_once "../conf/Conexao.php";
    require_once "../classes/usuario.class.php";

    $nome = isset($_POST['nome']) ? $_POST['nome'] : "";
    $login = isset($_POST['login']) ? $_POST['login'] : "";
    $senha = isset($_POST['senha']) ? $_POST['senha'] : "";

    $acao = "";
    if(isset($_POST['acao'])){$acao = $_POST['acao'];}else if(isset($_GET['acao'])){$acao = $_GET['acao'];}
        
    if ($acao == "excluir"){
        $idusuario = isset($_GET['idusuario']) ? $_GET['idusuario'] : 0;
        
        $usu = new Usuario("", "", "","");
        $resultado = $usu->excluir($idusuario);
        header("location:../usuario/listar.usuario.php");
    }

    if ($acao == "salvar"){
        $idusuario = isset($_POST['idusuario']) ? $_POST['idusuario'] : 0;
        if ($idusuario == 0){
            $usu = new Usuario("", $_POST['nome'], $_POST['login'],$_POST['senha']);      
            $resultado = $usu->salvar();
            header("location:../usuario/listar.usuario.php");
        }else {
            $usu = new Usuario($_POST['idusuario'], $_POST['nome'], $_POST['login'], $_POST['senha']);
            $resultado = $usu->editar();
        }    
        header("location:../usuario/listar.usuario.php");  
    }

?>