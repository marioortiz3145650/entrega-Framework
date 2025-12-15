<?php
// Libraries/Core/ConexionPg.php

class ConexionPg {
    private $conect;

    public function __construct() {
        if (defined('CONNECTION') && CONNECTION) {
            try {
                // Cadena de conexión para PostgreSQL
                $connectionString = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";";
                $this->conect = new PDO($connectionString, DB_USER, DB_PASSWORD);
                $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->conect = "Error de conexión";
                error_log("Error PostgreSQL: " . $e->getMessage());
                // No echo en producción; solo para desarrollo
                if (DEBUG) echo "ERROR: " . $e->getMessage();
            }
        }
    }

    public function connect() {
        return $this->conect;
    }
}