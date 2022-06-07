<?php

    //abre conexão com o banco de dados
    include_once "../conf/Conexao.php";
    require_once "../conf/conf.inc.php";

    //cria uma classe do objeto Quadrado
    class Quadrado{
        private $id;
        private $lado;
        private $cor;
        private $idTabuleiro;

        //constrói a classe através das variáveis 
        public function __construct($id, $lado, $cor, $idTabuleiro) {
            $this->setId($id);
            $this->setLado($lado);
            $this->setCor($cor);
            $this->setTabuleiro($idTabuleiro);
        }

        public function setId($id) {
            if ($id > 0)
                return  $this->id = $id ;
        }
        
        
        public function setLado($lado) {
            if ($lado > 0)
                return  $this->lado = $lado ;
        }

        public function setCor($cor) {
            if (strlen($cor) > 0)
                return  $this->cor = $cor ;
        }

        public function setTabuleiro($idTabuleiro) {
            if ($idTabuleiro > 0)
                return  $this->tabuleiro = $idTabuleiro ;
        }

        Public function getId () {
            return  $this->id;
        }

        Public function getLado () {
            return  $this->lado;
        }

        Public function getCor(){
            return  $this->cor;
        }

        Public function getTabuleiro(){
            return  $this ->tabuleiro;
        }

        public function Area() {
            //return $this->lado * $this->lado;
            $area = floatval($this->getLado()) * floatval($this->getLado());
            return $area;
        }

        public function Perimetro() {
            //return $this->lado * 4;
            $perimetro = floatval($this->getLado()) * 4;
            return $perimetro;
        }

        public function Diagonal() {
            //return $this->lado * sqrt(2);
            $diagonal = floatval($this->getLado()) * sqrt(2);
            return $diagonal;
        }
    
        public function __toString() {
            return  "[Quadrado]<br>Lado: ".$this->getLado()."<br>".
                    "Cor: ".$this->getCor()."<br>".
                    "Id do Tabuleiro: ".$this->getTabuleiro()."<br>".
                    "Area: ".$this->Area()."<br>".
                    "Perimetro: ".$this->Perimetro()."<br>".
                    "Diagonal: ".$this->Diagonal()."<br>".
                    "<br>";
        }

        public function salvar(){
            //abre conexão com o banco de dados 
            $pdo = Conexao::getInstance();
            //monta sql - comando para inserir os dados
            $stmt = $pdo->prepare('INSERT INTO recuperacao.quadrado (lado, cor, idtabuleiro) VALUES (:lado, :cor, :idtabuleiro)');
            //adiciona parâmetros
            $stmt->bindParam(':lado', $this->getLado(), PDO::PARAM_STR);
            $stmt->bindParam(':cor', $this->getCor(), PDO::PARAM_STR);
            $stmt->bindParam(':idtabuleiro', $this->getTabuleiro(), PDO::PARAM_STR);
            return $stmt->execute();
        }

        public function excluir($id){
            //abre conexão com o banco de dados 
            $pdo = Conexao::getInstance();
            //monta sql - comando para deletar os dados
            $stmt = $pdo->prepare('DELETE FROM recuperacao.quadrado WHERE id = :id');
            //adiciona parâmetros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function editar(){
            //abre conexao com o banco
            $pdo = Conexao::getInstance();
            //monta sql - comando para editar os dados
            $stmt = $pdo->prepare('UPDATE recuperacao.quadrado SET lado = :lado, cor = :cor, idtabuleiro = :idtabuleiro WHERE id = :id');
            //adiciona parâmetros
            $stmt->bindValue(':id', $this->getId() , PDO::PARAM_INT);
            $stmt->bindValue(':lado', $this->getLado(), PDO::PARAM_STR);
            $stmt->bindValue(':cor', $this->getCor(), PDO::PARAM_STR);
            $stmt->bindValue(':idtabuleiro', $this->getTabuleiro(), PDO::PARAM_INT);
            return $stmt->execute();
        }

        //desenha o quadrado
        public function desenhar(){
            $str = "<div style='width: ".$this->getLado()."px; height: ".$this->getLado()."px; background: ".$this->getCor(). ";border: 7px solid;'></div>";
            return $str;
        }
    
        

        public function listar($buscar = 0, $procurar = ""){
            //abrir conexao com o banco
            $pdo = Conexao::getInstance();
            //montar sql - comando para inserir os dados
            $sql = "SELECT * FROM recuperacao.quadrado";
            //estrutura a busca por dados especificos (pesquisa)
            if ($buscar > 0)
                switch($buscar){
                    case(1): $sql .= " WHERE id LIKE :procurar"; $procurar = "%".$procurar."%";break;
                    case(2): $sql .= " WHERE lado LIKE :procurar"; $procurar.="%";  break;
                    case(3): $sql .= " WHERE cor LIKE :procurar"; $procurar = "%".$procurar."%"; break;
                    case(4): $sql .= " WHERE idtabuleiro LIKE :procurar"; $procurar = "%".$procurar."%";  break;
                }
                //adiciona parâmetros
                $stmt = $pdo->prepare($sql);
                if ($buscar > 0)
                    $stmt->bindValue(':procurar',$procurar,PDO::PARAM_STR);
            $stmt->execute();   
            return $stmt->fetchALL();
        }

    }

?>