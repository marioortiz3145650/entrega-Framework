<?php

class UsuariosModel extends Postgres
{
    public function __construct()
    {   
        parent::__construct();
    }

    public function setCliente(
        string $strNombre,
        string $strEmail,
        string $strUsername,
        string $strPasswordHash,
        int $intRolId,
        bool $boolActivo
    ) {
        // Validar duplicados
        $sql = "SELECT id_usuario FROM usuarios 
                WHERE email = :email OR username = :username";
        $exists = $this->select($sql, [
            ':email' => $strEmail,
            ':username' => $strUsername
        ]);

        if (!empty($exists)) {
            error_log("Usuario ya existe: email={$strEmail}, username={$strUsername}");
            return false;
        }

        // Insertar los usuarios
        $query = "INSERT INTO usuarios(
                    nombre, email, username, password_hash, rol_id, activo
                  ) VALUES (
                    :nombre, :email, :username, :password_hash, :rol_id, :activo
                  ) RETURNING id_usuario;";

        $data = [
            ':nombre' => $strNombre,
            ':email' => $strEmail,
            ':username' => $strUsername,
            ':password_hash' => $strPasswordHash,
            ':rol_id' => $intRolId,
            ':activo' => $boolActivo
        ];

        try {
            $result = $this->insert($query, $data);
            if ($result) {
                error_log("Usuario insertado correctamente, ID: $result");
                return $result;
            } else {
                error_log("Insert falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al insertar usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>