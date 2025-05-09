<?php
class Database
{
    private $host = "localhost";
    private $db_name = "minisuperVariedadesEscobar";
    private $username = "wilfredo";  // Cambiar si es necesario
    private $password = "wilfredo3026";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo json_encode(["error" => "Conexión fallida: " . $exception->getMessage()]);
            $this->conn = null;
        }

        return $this->conn;
    }
}
?>