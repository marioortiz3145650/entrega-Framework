<?php
// Libraries/Core/Postgres.php

require_once 'ConexionPg.php';

class Postgres {
    private $conexion;
    private $strquery;
    private $arrValues;

    public function __construct() {
        $this->conexion = new ConexionPg();
        $this->conexion = $this->conexion->connect();
    }

    // Insertar un registro
    public function insert(string $query, array $arrValues) {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $insert = $this->conexion->prepare($this->strquery);
            $resInsert = $insert->execute($this->arrValues);
            
            // En PostgreSQL, lastInsertId() solo funciona si usas SERIAL y devuelves el id
            // Si tu tabla tiene una columna SERIAL (ej. id), puedes usar:
            // $id = $this->conexion->lastInsertId(); // ¡Pero solo si usas RETURNING id!
            // Alternativa: mejor devolver el ID desde la consulta con RETURNING

            $insert->closeCursor();
            return $resInsert ? $this->conexion->lastInsertId() : 0;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            error_log($response);
            return $response;
        }
    }

    // Devuelve todos los registros
    public function select_all(string $query) {
        try {
            $this->strquery = $query;
            $execute = $this->conexion->query($this->strquery);
            $request = $execute->fetchAll(PDO::FETCH_ASSOC);
            $execute->closeCursor();
            return $request;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            error_log($response);
            return $response;
        }
    }

    // Busca un registro
    public function select(string $query, array $arrValues) {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $stmt = $this->conexion->prepare($this->strquery);
            $stmt->execute($this->arrValues);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $request;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            error_log($response);
            return $response;
        }
    }

    // Actualiza registros
    public function update(string $query, array $arrValues) {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $update = $this->conexion->prepare($this->strquery);
            $resUpdate = $update->execute($this->arrValues);
            $update->closeCursor();
            return $resUpdate;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            error_log($response);
            return $response;
        }
    }

    // Eliminar un registro
    public function delete(string $query, array $arrValues) {
        try {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $delete = $this->conexion->prepare($this->strquery);
            $del = $delete->execute($this->arrValues);
            $delete->closeCursor();
            return $del;
        } catch (Exception $e) {
            $response = "Error: " . $e->getMessage();
            error_log($response);
            return $response;
        }
    }
}