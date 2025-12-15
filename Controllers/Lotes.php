<?php

require_once __DIR__ . '/../Libraries/Core/Controllers.php';

class Lotes extends Controllers
{

    public function __construct()
    {
        parent::__construct();
    }

    public function registro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = [
                'status' => false,
                'msg'    => 'Método no permitido. Use POST.',
            ];
            jsonResponse($response, 405);
            die();
        }

        // Leer JSON
        $_POST = json_decode(file_get_contents('php://input'), true);


        if (! is_array($_POST)) {
            $response = ['status' => false, 'msg' => 'Error: Datos no válidos'];
            jsonResponse($response, 400);
            die();
        }

        // Validaciones
        if (empty($_POST['codigo']) || ! preg_match('/^[a-zA-Z0-9]+$/', $_POST['codigo'])) {
            $response = ['status' => false, 'msg' => 'Error en el código del lote'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['galpon_id']) || ! testEntero($_POST['galpon_id'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del galpón'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['raza_id']) || ! testEntero($_POST['raza_id'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID de la raza'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['edad_semanas']) || ! testEntero($_POST['edad_semanas'])) {
            $response = ['status' => false, 'msg' => 'Error en la edad en semanas'];
            jsonResponse($response, 400);
            die();
        }
        if (isset($_POST['produccion_pct']) && ! testDecimal($_POST['produccion_pct'])) {
            $response = ['status' => false, 'msg' => 'Error en el porcentaje de producción'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['fecha_inicio']) || ! testDate($_POST['fecha_inicio'])) {
            $response = ['status' => false, 'msg' => 'Error en la fecha de inicio'];
            jsonResponse($response, 400);
            die();
        }
        if (isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) && ! testDate($_POST['fecha_fin'])) {
            $response = ['status' => false, 'msg' => 'Error en la fecha de fin'];
            jsonResponse($response, 400);
            die();
        }


        $strCodigo         = trim($_POST['codigo']);
        $intGalponId       = (int) $_POST['galpon_id'];
        $intRazaId         = (int) $_POST['raza_id'];
        $intEdadSemanas    = (int) $_POST['edad_semanas'];
        $floatProduccionPct = isset($_POST['produccion_pct']) ? (float) $_POST['produccion_pct'] : null;
        $strFechaInicio    = $_POST['fecha_inicio'];
        $strFechaFin       = isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;


        require_once __DIR__ . '/../Models/LotesModel.php';
        $this->model = new LotesModel();

        $request = $this->model->setLote(

            $strCodigo,
            $intGalponId,
            $intRazaId,
            $intEdadSemanas,
            $floatProduccionPct,
            $strFechaInicio,
            $strFechaFin
        );


        if ($request && $request > 0) {
            $response = [
                'status'     => true,
                'msg'        => 'Lote agregado correctamente',
                'id_lote' => $request,
            ];
            jsonResponse($response, 201);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo registrar el lote. Puede que ya exista.',
            ];
            jsonResponse($response, 409);
        }
        die();

    }

    public function editar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $response = [
                'status' => false,
                'msg'    => 'Método no permitido. Use PUT.',
            ];
            jsonResponse($response, 405);
            die();
        }

        // Leer JSON
        $_PUT = json_decode(file_get_contents('php://input'), true);

        if (! is_array($_PUT)) {
            $response = ['status' => false, 'msg' => 'Error: Datos no válidos'];
            jsonResponse($response, 400);
            die();
        }

        // Validaciones
        if (empty($_PUT['id_lote']) || ! testEntero($_PUT['id_lote'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del lote'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['codigo']) || ! preg_match('/^[a-zA-Z0-9]+$/', $_PUT['codigo'])) {
            $response = ['status' => false, 'msg' => 'Error en el código del lote'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['galpon_id']) || ! testEntero($_PUT['galpon_id'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del galpón'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['raza_id']) || ! testEntero($_PUT['raza_id'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID de la raza'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['edad_semanas']) || ! testEntero($_PUT['edad_semanas'])) {
            $response = ['status' => false, 'msg' => 'Error en la edad en semanas'];
            jsonResponse($response, 400);
            die();
        }
        if (isset($_PUT['produccion_pct']) && ! testDecimal($_PUT['produccion_pct'])) {
            $response = ['status' => false, 'msg' => 'Error en el porcentaje de producción'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['fecha_inicio']) || ! testDate($_PUT['fecha_inicio'])) {
            $response = ['status' => false, 'msg' => 'Error en la fecha de inicio'];
            jsonResponse($response, 400);
            die();
        }
        if (isset($_PUT['fecha_fin']) && !empty($_PUT['fecha_fin']) && ! testDate($_PUT['fecha_fin'])) {
            $response = ['status' => false, 'msg' => 'Error en la fecha de fin'];
            jsonResponse($response, 400);
            die();
        }

        $intIdLote         = (int) $_PUT['id_lote'];
        $strCodigo         = trim($_PUT['codigo']);
        $intGalponId       = (int) $_PUT['galpon_id'];
        $intRazaId         = (int) $_PUT['raza_id'];
        $intEdadSemanas    = (int) $_PUT['edad_semanas'];
        $floatProduccionPct = isset($_PUT['produccion_pct']) ? (float) $_PUT['produccion_pct'] : null;
        $strFechaInicio    = $_PUT['fecha_inicio'];
        $strFechaFin       = isset($_PUT['fecha_fin']) && !empty($_PUT['fecha_fin']) ? $_PUT['fecha_fin'] : null;

        require_once __DIR__ . '/../Models/LotesModel.php';
        $this->model = new LotesModel();

        $request = $this->model->updateLote(
            $intIdLote,
            $strCodigo,
            $intGalponId,
            $intRazaId,
            $intEdadSemanas,
            $floatProduccionPct,
            $strFechaInicio,
            $strFechaFin
        );

        if ($request) {
            $response = [
                'status'     => true,
                'msg'        => 'Lote actualizado correctamente',
            ];
            jsonResponse($response, 200);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo actualizar el lote. Puede que ya exista.',
            ];
            jsonResponse($response, 409);
        }
        die();

    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $response = [
                'status' => false,
                'msg'    => 'Método no permitido. Use DELETE.',
            ];
            jsonResponse($response, 405);
            die();
        }

        // Leer JSON
        $_DELETE = json_decode(file_get_contents('php://input'), true);

        if (! is_array($_DELETE)) {
            $response = ['status' => false, 'msg' => 'Error: Datos no válidos'];
            jsonResponse($response, 400);
            die();
        }

        // Validaciones
        if (empty($_DELETE['id_lote']) || ! testEntero($_DELETE['id_lote'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del lote'];
            jsonResponse($response, 400);
            die();
        }

        $intIdLote = (int) $_DELETE['id_lote'];

        require_once __DIR__ . '/../Models/LotesModel.php';
        $this->model = new LotesModel();

        $request = $this->model->deleteLote($intIdLote);

        if ($request) {
            $response = [
                'status'     => true,
                'msg'        => 'Lote eliminado correctamente',
            ];
            jsonResponse($response, 200);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo eliminar el lote.',
            ];
            jsonResponse($response, 500);
        }
        die();

    }

}