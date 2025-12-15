<?php

class LotesModel extends Postgres
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setLote(
        string $strCodigo,
        int $intGalponId,
        int $intRazaId,
        int $intEdadSemanas,
        ?float $floatProduccionPct,
        string $strFechaInicio,
        ?string $strFechaFin
    ) {
        // Validar duplicados por código
        $sql = "SELECT id_lote FROM lotes WHERE codigo = :codigo";
        $exists = $this->select($sql, [':codigo' => $strCodigo]);

        if (!empty($exists)) {
            error_log("Lote ya existe con código: {$strCodigo}");
            return false;
        }

        // Insertar el lote
        $query = "INSERT INTO lotes(
                    codigo, galpon_id, raza_id, edad_semanas, produccion_pct, fecha_inicio, fecha_fin
                  ) VALUES (
                    :codigo, :galpon_id, :raza_id, :edad_semanas, :produccion_pct, :fecha_inicio, :fecha_fin
                  ) RETURNING id_lote;";

        $data = [
            ':codigo' => $strCodigo,
            ':galpon_id' => $intGalponId,
            ':raza_id' => $intRazaId,
            ':edad_semanas' => $intEdadSemanas,
            ':produccion_pct' => $floatProduccionPct,
            ':fecha_inicio' => $strFechaInicio,
            ':fecha_fin' => $strFechaFin
        ];

        try {
            $result = $this->insert($query, $data);
            if ($result) {
                error_log("Lote insertado correctamente, ID: $result");
                return $result;
            } else {
                error_log("Insert falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al insertar lote: " . $e->getMessage());
            return false;
        }
    }

    public function updateLote(
        int $idLote,
        string $strCodigo,
        int $intGalponId,
        int $intRazaId,
        int $intEdadSemanas,
        ?float $floatProduccionPct,
        string $strFechaInicio,
        ?string $strFechaFin
    ) {
        // Validar duplicados, excluyendo el lote actual
        $sql = "SELECT id_lote FROM lotes WHERE codigo = :codigo AND id_lote != :id_lote";
        $exists = $this->select($sql, [
            ':codigo' => $strCodigo,
            ':id_lote' => $idLote
        ]);

        if (!empty($exists)) {
            error_log("Lote ya existe con código: {$strCodigo}");
            return false;
        }

        // Actualizar el lote
        $query = "UPDATE lotes SET
                    codigo = :codigo,
                    galpon_id = :galpon_id,
                    raza_id = :raza_id,
                    edad_semanas = :edad_semanas,
                    produccion_pct = :produccion_pct,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin
                  WHERE id_lote = :id_lote";

        $data = [
            ':codigo' => $strCodigo,
            ':galpon_id' => $intGalponId,
            ':raza_id' => $intRazaId,
            ':edad_semanas' => $intEdadSemanas,
            ':produccion_pct' => $floatProduccionPct,
            ':fecha_inicio' => $strFechaInicio,
            ':fecha_fin' => $strFechaFin,
            ':id_lote' => $idLote
        ];

        try {
            $result = $this->update($query, $data);
            if ($result) {
                error_log("Lote actualizado correctamente, ID: $idLote");
                return true;
            } else {
                error_log("Actualización falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar lote: " . $e->getMessage());
            return false;
        }
    }

    public function deleteLote(int $idLote) {
        $query = "DELETE FROM lotes WHERE id_lote = :id_lote";

        $data = [':id_lote' => $idLote];

        try {
            $result = $this->delete($query, $data);
            if ($result) {
                error_log("Lote eliminado correctamente, ID: $idLote");
                return true;
            } else {
                error_log("Eliminación falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar lote: " . $e->getMessage());
            return false;
        }
    }
}
?>