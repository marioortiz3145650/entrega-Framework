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
        bool $boolEstado
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
                    nombre, email, username, password_hash, rol_id, estado
                  ) VALUES (
                    :nombre, :email, :username, :password_hash, :rol_id, :estado
                  ) RETURNING id_usuario;";

        $data = [
            ':nombre' => $strNombre,
            ':email' => $strEmail,
            ':username' => $strUsername,
            ':password_hash' => $strPasswordHash,
            ':rol_id' => $intRolId,
            ':estado' => $boolEstado
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

    public function updateUsuario(
        int $idUsuario,
        string $strNombre,
        string $strEmail,
        string $strUsername,
        int $intRolId,
        bool $boolEstado
    ) {
        // Validar duplicados, excluyendo el usuario actual
        $sql = "SELECT id_usuario FROM usuarios
                WHERE (email = :email OR username = :username) AND id_usuario != :id_usuario";
        $exists = $this->select($sql, [
            ':email' => $strEmail,
            ':username' => $strUsername,
            ':id_usuario' => $idUsuario
        ]);

        if (!empty($exists)) {
            error_log("Usuario ya existe: email={$strEmail}, username={$strUsername}");
            return false;
        }

        // Actualizar el usuario
        $query = "UPDATE usuarios SET
                    nombre = :nombre,
                    email = :email,
                    username = :username,
                    rol_id = :rol_id,
                    estado = :estado
                  WHERE id_usuario = :id_usuario";

        $data = [
            ':nombre' => $strNombre,
            ':email' => $strEmail,
            ':username' => $strUsername,
            ':rol_id' => $intRolId,
            ':estado' => $boolEstado,
            ':id_usuario' => $idUsuario
        ];

        try {
            $result = $this->update($query, $data);
            if ($result) {
                error_log("Usuario actualizado correctamente, ID: $idUsuario");
                return true;
            } else {
                error_log("Actualización falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUsuario(int $idUsuario) {
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";

        $data = [':id_usuario' => $idUsuario];

        try {
            $result = $this->delete($query, $data);
            if ($result) {
                error_log("Usuario eliminado correctamente, ID: $idUsuario");
                return true;
            } else {
                error_log("Eliminación falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>