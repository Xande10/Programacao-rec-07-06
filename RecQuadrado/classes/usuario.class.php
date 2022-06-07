<?php

    include_once "../conf/Conexao.php";
    require_once "../conf/conf.inc.php";

    class Usuario{
        private $id;
        private $nome;
        private $login;
        private $senha;

        //constrói a classe através das variáveis
        public function __construct($id, $nome, $login, $senha) {
            $this->setId($id);
            $this->setNome($nome);
            $this->setLogin($login);
            $this->setSenha($senha);
        }

        public function __toString() {
            return  "[Usuario] <br>Id: ".$this->getId(). "</br>".
                    "Nome: ".$this->getNome()."<br>".
                    "Login: ".$this->getLogin()."<br>".
                    "Senha: ".$this->getSenha()."<br>";

        }

        public function setId($id) {
            if ($id > 0)
                return  $this -> id = $id ;
        }
        
        
        public function setNome($nome) {
            if (strlen($nome) > 0)
                return  $this -> nome = $nome ;
        }

        public function setLogin($login) {
            if (strlen($login) > 0)
                return  $this -> login = $login ;
        }

        public function setSenha($senha) {
            if (strlen($senha) > 0)
                return  $this -> senha = $senha ;
        }

        Public function getId () {
            return  $this->id;
        }

        Public function getNome () {
            return  $this->nome;
        }

        Public function getLogin(){
            return  $this ->login;
        }

        Public function getSenha(){
            return  $this ->senha;
        }

        public function salvar(){
            //abrir conexao com o banco
            $pdo = Conexao::getInstance();
            //montar sql - comando para inserir os dados
            $stmt = $pdo->prepare('INSERT INTO recuperacao.usuario (nome, login, senha) VALUES (:nome, :login, :senha)');
            //adicionar parâmetros
            $stmt->bindParam(':nome',$this->getNome(), PDO::PARAM_STR);
            $stmt->bindParam(':login',$this->getLogin(), PDO::PARAM_STR);
            $stmt->bindParam(':senha',$this->getSenha(), PDO::PARAM_STR);
            return $stmt->execute();
        }

        public function excluir($id){
            //abre conexao com o banco
            $pdo = Conexao::getInstance();
            //monta sql - comando para inserir os dados
            $stmt = $pdo->prepare('DELETE FROM recuperacao.usuario WHERE idusuario = :idusuario');
            //adiciona parâmetros
            $stmt->bindParam(':idusuario', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function editar(){
            //abre a conexao com o banco
            $pdo = Conexao::getInstance();
            //monta sql - comando para inserir os dados
            $stmt = $pdo->prepare('UPDATE recuperacao.usuario SET nome = :nome, login = :login, senha = :senha WHERE idusuario = :idusuario');
            //adiciona parâmetros
            $stmt->bindValue(':idusuario', $this->getId());
            $stmt->bindValue(':nome', $this->getNome());
            $stmt->bindValue(':login', $this->getLogin());
            $stmt->bindValue(':senha', $this->getsenha());
            return $stmt->execute();
        }

        /*public function Dados($colunas="*", $condicao = null, $pesquisa = null){
            $sql = "SELECT $colunas FROM usuario";
            if($condicao != null){
                $sql .= " WHERE $condicao";
                if($pesquisa != null){
                    if(is_numeric($pesquisa) == false) {
                        $sql .= "LIKE '%" .trim($pesquisa) . "%'";
                    }else if(is_numeric($pesquisa) == true) {
                        $sql .= " <= '" .trim($pesquisa) . "'";
                    }
                }
            }
            $sql .= ";";
            $pdo = Conexao::getInstance();
            return $pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
        }*/
        

        /*public function Login($login, $senha){
            $pdo = Conexao::getInstance();
            $examinar = $this->Dados('nome', "login = '$login' AND senha = '$senha'");
            if ($examinar){
                $_SESSION["nome"] = $examinar[0]['nome'];
                return true;
            }else {
                return false;
            }
        }*/

        //efetua a função de login
        public function Login($Login, $senha){
            $pdo = Conexao::getInstance();
            $sql = "SELECT nome FROM usuario WHERE login = '$Login' AND senha = '$senha';";
            $r = $pdo->query($sql)->fetchAll();
            if($r){
                $_SESSION['nome'] = $r[0]['nome'];
                return true;
            } else {
                $_SESSION['nome'] = null;
                return false;
            }
        
        }

        public function listar($buscar = 0, $procurar = ""){
            //abrir conexao com o banco
            $pdo = Conexao::getInstance();
            //montar sql - comando para inserir os dados
            $sql = "SELECT * FROM recuperacao.usuario";
            //adicionar parâmetros
            if ($buscar > 0)
                switch($buscar){
                    case(1): $sql .= " WHERE idusuario = :procurar"; break;
                    case(2): $sql .= " WHERE nome LIKE :procurar"; $procurar = "%".$procurar."%";  break;
                    case(3): $sql .= " WHERE login LIKE :procurar"; $procurar = "%".$procurar."%"; break;
                    case(4): $sql .= " WHERE senha LIKE :procurar"; $procurar.="%";  break;
                }
                $stmt = $pdo->prepare($sql);
                if ($buscar > 0)
                    $stmt->bindValue(':procurar',$procurar,PDO::PARAM_STR);
            $stmt->execute();   
            return $stmt->fetchALL();
        }

    }

?>