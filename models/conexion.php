<?php 
require_once __DIR__ . '/../config/env.php';

class Conexion {
    private $host; 
    private $usuario;
    private $clave; 
    private $db; 
    private $puerto; 

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->usuario = getenv('DB_USER');
        $this->clave = getenv('DB_PASSWORD');
        $this->db = getenv('DB_NAME');
        $this->puerto = getenv('DB_PORT');

    }

    public function conectar() {
        $puerto = ($this->puerto !== false && $this->puerto !== null && $this-> puerto !== '') ?(int)$this->puerto: null;

        $conexion =$puerto !== null ? new mysqli($this->host,$this->usuario,$this->clave,$this->db,$puerto): 
        new mysqli($this->host,$this->usuario,$this->clave,$this->db);
        if($conexion->connect_error){
            throw new Exception("revisa tus credenciales en tu archivo de entorno: ".$conexion->connect_error);
        }
        return $conexion;
    }

    public function desconectar() {
        
    }
}