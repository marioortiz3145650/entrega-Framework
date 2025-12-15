<?php

require_once __DIR__ . '/../Libraries/Core/Controllers.php';

class Produccion extends Controllers
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
        if (empty($_POST['fecha']) || ! testDate($_POST['fecha'])) {
            $response = ['status' => false, 'msg' => 'Error en la fecha'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['lote_id']) || ! testEntero($_POST['lote_id'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del lote'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_POST['jumbo']) || ! testEntero($_POST['jumbo'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad Jumbo'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_POST['aaa']) || ! testEntero($_POST['aaa'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad AAA'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_POST['aa']) || ! testEntero($_POST['aa'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad AA'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_POST['a']) || ! testEntero($_POST['a'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad A'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_POST['b']) || ! testEntero($_POST['b'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad B'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_POST['c']) || ! testEntero($_POST['c'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad C'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['creado_por']) || ! testEntero($_POST['creado_por'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del usuario creador'];
            jsonResponse($response, 400);
            die();
        }


        $strFecha     = $_POST['fecha'];
        $intLoteId    = (int) $_POST['lote_id'];
        $intJumbo     = (int) $_POST['jumbo'];
        $intAaa       = (int) $_POST['aaa'];
        $intAa        = (int) $_POST['aa'];
        $intA         = (int) $_POST['a'];
        $intB         = (int) $_POST['b'];
        $intC         = (int) $_POST['c'];
        $intCreadoPor = (int) $_POST['creado_por'];


        require_once __DIR__ . '/../Models/ProduccionModel.php';
        $this->model = new ProduccionModel();

        $request = $this->model->setProduccion(

            $strFecha,
            $intLoteId,
            $intJumbo,
            $intAaa,
            $intAa,
            $intA,
            $intB,
            $intC,
            $intCreadoPor
        );


        if ($request && $request > 0) {
            $response = [
                'status'         => true,
                'msg'            => 'Producción agregada correctamente',
                'id_produccion' => $request,
            ];
            jsonResponse($response, 201);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo registrar la producción. Puede que ya exista para esta fecha y lote.',
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
        if (empty($_PUT['id_produccion']) || ! testEntero($_PUT['id_produccion'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID de la producción'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['fecha']) || ! testDate($_PUT['fecha'])) {
            $response = ['status' => false, 'msg' => 'Error en la fecha'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['lote_id']) || ! testEntero($_PUT['lote_id'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del lote'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['jumbo']) || ! testEntero($_PUT['jumbo'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad Jumbo'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['aaa']) || ! testEntero($_PUT['aaa'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad AAA'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['aa']) || ! testEntero($_PUT['aa'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad AA'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['a']) || ! testEntero($_PUT['a'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad A'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['b']) || ! testEntero($_PUT['b'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad B'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['c']) || ! testEntero($_PUT['c'])) {
            $response = ['status' => false, 'msg' => 'Error en la cantidad C'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['creado_por']) || ! testEntero($_PUT['creado_por'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del usuario creador'];
            jsonResponse($response, 400);
            die();
        }

        $intIdProduccion = (int) $_PUT['id_produccion'];
        $strFecha        = $_PUT['fecha'];
        $intLoteId       = (int) $_PUT['lote_id'];
        $intJumbo        = (int) $_PUT['jumbo'];
        $intAaa          = (int) $_PUT['aaa'];
        $intAa           = (int) $_PUT['aa'];
        $intA            = (int) $_PUT['a'];
        $intB            = (int) $_PUT['b'];
        $intC            = (int) $_PUT['c'];
        $intCreadoPor    = (int) $_PUT['creado_por'];

        require_once __DIR__ . '/../Models/ProduccionModel.php';
        $this->model = new ProduccionModel();

        $request = $this->model->updateProduccion(
            $intIdProduccion,
            $strFecha,
            $intLoteId,
            $intJumbo,
            $intAaa,
            $intAa,
            $intA,
            $intB,
            $intC,
            $intCreadoPor
        );

        if ($request) {
            $response = [
                'status'     => true,
                'msg'        => 'Producción actualizada correctamente',
            ];
            jsonResponse($response, 200);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo actualizar la producción. Puede que ya exista para esta fecha y lote.',
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
        if (empty($_DELETE['id_produccion']) || ! testEntero($_DELETE['id_produccion'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID de la producción'];
            jsonResponse($response, 400);
            die();
        }

        $intIdProduccion = (int) $_DELETE['id_produccion'];

        require_once __DIR__ . '/../Models/ProduccionModel.php';
        $this->model = new ProduccionModel();

        $request = $this->model->deleteProduccion($intIdProduccion);

        if ($request) {
            $response = [
                'status'     => true,
                'msg'        => 'Producción eliminada correctamente',
            ];
            jsonResponse($response, 200);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo eliminar la producción.',
            ];
            jsonResponse($response, 500);
        }
        die();

    }

}