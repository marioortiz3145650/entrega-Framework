<?php

class ProduccionModel extends Postgres
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setProduccion(
        string $strFecha,
        int $intLoteId,
        int $intJumbo,
        int $intAaa,
        int $intAa,
        int $intA,
        int $intB,
        int $intC,
        int $intCreadoPor
    ) {
        // Validar que no exista producción para la misma fecha y lote
        $sql = "SELECT id_produccion FROM produccion WHERE fecha = :fecha AND lote_id = :lote_id";
        $exists = $this->select($sql, [
            ':fecha' => $strFecha,
            ':lote_id' => $intLoteId
        ]);

        if (!empty($exists)) {
            error_log("Producción ya existe para la fecha {$strFecha} y lote {$intLoteId}");
            return false;
        }

        // Calcular total
        $intTotal = $intJumbo + $intAaa + $intAa + $intA + $intB + $intC;

        // Insertar la producción
        $query = "INSERT INTO produccion(
                    fecha, lote_id, jumbo, aaa, aa, a, b, c, total, creado_por
                  ) VALUES (
                    :fecha, :lote_id, :jumbo, :aaa, :aa, :a, :b, :c, :total, :creado_por
                  ) RETURNING id_produccion;";

        $data = [
            ':fecha' => $strFecha,
            ':lote_id' => $intLoteId,
            ':jumbo' => $intJumbo,
            ':aaa' => $intAaa,
            ':aa' => $intAa,
            ':a' => $intA,
            ':b' => $intB,
            ':c' => $intC,
            ':total' => $intTotal,
            ':creado_por' => $intCreadoPor
        ];

        try {
            $result = $this->insert($query, $data);
            if ($result) {
                error_log("Producción insertada correctamente, ID: $result");
                return $result;
            } else {
                error_log("Insert falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al insertar producción: " . $e->getMessage());
            return false;
        }
    }

    public function updateProduccion(
        int $idProduccion,
        string $strFecha,
        int $intLoteId,
        int $intJumbo,
        int $intAaa,
        int $intAa,
        int $intA,
        int $intB,
        int $intC,
        int $intCreadoPor
    ) {
        // Validar que no exista producción para la misma fecha y lote (excluyendo el registro actual)
        $sql = "SELECT id_produccion FROM produccion WHERE fecha = :fecha AND lote_id = :lote_id AND id_produccion != :id_produccion";
        $exists = $this->select($sql, [
            ':fecha' => $strFecha,
            ':lote_id' => $intLoteId,
            ':id_produccion' => $idProduccion
        ]);

        if (!empty($exists)) {
            error_log("Producción ya existe para la fecha {$strFecha} y lote {$intLoteId}");
            return false;
        }

        // Calcular total
        $intTotal = $intJumbo + $intAaa + $intAa + $intA + $intB + $intC;

        // Actualizar la producción
        $query = "UPDATE produccion SET
                    fecha = :fecha,
                    lote_id = :lote_id,
                    jumbo = :jumbo,
                    aaa = :aaa,
                    aa = :aa,
                    a = :a,
                    b = :b,
                    c = :c,
                    total = :total,
                    creado_por = :creado_por
                  WHERE id_produccion = :id_produccion";

        $data = [
            ':fecha' => $strFecha,
            ':lote_id' => $intLoteId,
            ':jumbo' => $intJumbo,
            ':aaa' => $intAaa,
            ':aa' => $intAa,
            ':a' => $intA,
            ':b' => $intB,
            ':c' => $intC,
            ':total' => $intTotal,
            ':creado_por' => $intCreadoPor,
            ':id_produccion' => $idProduccion
        ];

        try {
            $result = $this->update($query, $data);
            if ($result) {
                error_log("Producción actualizada correctamente, ID: $idProduccion");
                return true;
            } else {
                error_log("Actualización falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar producción: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProduccion(int $idProduccion) {
        $query = "DELETE FROM produccion WHERE id_produccion = :id_produccion";

        $data = [':id_produccion' => $idProduccion];

        try {
            $result = $this->delete($query, $data);
            if ($result) {
                error_log("Producción eliminada correctamente, ID: $idProduccion");
                return true;
            } else {
                error_log("Eliminación falló, pero sin excepción");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar producción: " . $e->getMessage());
            return false;
        }
    }
}
?>