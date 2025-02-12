<?php
require '../../vendor/autoload.php';
use Dotenv\Dotenv;

class SQL {
    private $pdo;
    private $host;
    private $dbname;
    private $user;
    private $pass;

    public function __construct() {
		
        try {
            $dotenv = Dotenv::createImmutable('../../');
            $dotenv->load();
            $this->host = $_ENV['DB_HOST'];
            $this->dbname = $_ENV['DB_NAME'];
            $this->user = $_ENV['DB_USER'];
            $this->pass = $_ENV['DB_PASS'];

            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta SQL y devuelve los resultados.
     *
     * @param string $sql  Consulta SQL con placeholders.
     * @param array  $params  Parámetros para la consulta (opcional).
     * @param bool   $asObject  `true` para devolver objetos, `false` para arrays.
     *
     * @return array  Resultados de la consulta.
     */
    public function query($sql, $params = [], $asObject = false) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $asObject ? $stmt->fetchAll(PDO::FETCH_OBJ) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
}
