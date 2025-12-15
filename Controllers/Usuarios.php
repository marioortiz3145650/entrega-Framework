<?php

require_once __DIR__ . '/../Libraries/Core/Controllers.php';

class Usuarios extends Controllers
{

    public function __construct()
    {
        parent::__construct();
    }

    public function usuarios($idUsuario)
    {
        echo "Hola desde usuarios con el id: " . $idUsuario;
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
        if (empty($_POST['nombre']) || ! testString($_POST['nombre'])) {
            $response = ['status' => false, 'msg' => 'Error en el nombre'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['email']) || ! testEmail($_POST['email'])) {
            $response = ['status' => false, 'msg' => 'Error en el email'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['username']) || ! testUsername($_POST['username'])) {
            $response = ['status' => false, 'msg' => 'Error en el username'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['password']) || ! testPassword($_POST['password'])) {
            $response = ['status' => false, 'msg' => 'Error en la contraseña'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['rolid']) || ! testEntero($_POST['rolid'])) {
            $response = ['status' => false, 'msg' => 'Error en el rol ID'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_POST['estado']) || ! testBooleanString($_POST['estado'])) {
            $response = ['status' => false, 'msg' => 'Error en el estado'];
            jsonResponse($response, 400);
            die();
        }

        
        $strNombre         = ucwords(strtolower(trim($_POST['nombre'])));
        $strEmail          = strtolower(trim($_POST['email']));
        $strUsername       = strtolower(trim($_POST['username']));
        $strPasswordHash   = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $intRolId          = (int) $_POST['rolid'];
        $boolEstado        = filter_var($_POST['estado'], FILTER_VALIDATE_BOOLEAN);

        
        require_once __DIR__ . '/../Models/UsuariosModel.php';
        $this->model = new UsuariosModel();

        $request = $this->model->setCliente(

            $strNombre,
            $strEmail,
            $strUsername,
            $strPasswordHash,
            $intRolId,
            $boolEstado
        );

        
        if ($request && $request > 0) {
            $response = [
                'status'     => true,
                'msg'        => 'Datos agregados correctamente',
                'id_usuario' => $request,
            ];
            jsonResponse($response, 201);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo registrar el usuario. Puede que ya exista.',
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
        if (empty($_PUT['id_usuario']) || ! testEntero($_PUT['id_usuario'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del usuario'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['nombre']) || ! testString($_PUT['nombre'])) {
            $response = ['status' => false, 'msg' => 'Error en el nombre'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['email']) || ! testEmail($_PUT['email'])) {
            $response = ['status' => false, 'msg' => 'Error en el email'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['username']) || ! testUsername($_PUT['username'])) {
            $response = ['status' => false, 'msg' => 'Error en el username'];
            jsonResponse($response, 400);
            die();
        }
        if (empty($_PUT['rolid']) || ! testEntero($_PUT['rolid'])) {
            $response = ['status' => false, 'msg' => 'Error en el rol ID'];
            jsonResponse($response, 400);
            die();
        }
        if (!isset($_PUT['estado']) || ! testBooleanString($_PUT['estado'])) {
            $response = ['status' => false, 'msg' => 'Error en el estado'];
            jsonResponse($response, 400);
            die();
        }

        $intIdUsuario     = (int) $_PUT['id_usuario'];
        $strNombre         = ucwords(strtolower(trim($_PUT['nombre'])));
        $strEmail          = strtolower(trim($_PUT['email']));
        $strUsername       = strtolower(trim($_PUT['username']));
        $intRolId          = (int) $_PUT['rolid'];
        $boolEstado        = filter_var($_PUT['estado'], FILTER_VALIDATE_BOOLEAN);

        require_once __DIR__ . '/../Models/UsuariosModel.php';
        $this->model = new UsuariosModel();

        $request = $this->model->updateUsuario(
            $intIdUsuario,
            $strNombre,
            $strEmail,
            $strUsername,
            $intRolId,
            $boolEstado
        );

        if ($request) {
            $response = [
                'status'     => true,
                'msg'        => 'Datos actualizados correctamente',
            ];
            jsonResponse($response, 200);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo actualizar el usuario. Puede que ya exista.',
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
        if (empty($_DELETE['id_usuario']) || ! testEntero($_DELETE['id_usuario'])) {
            $response = ['status' => false, 'msg' => 'Error en el ID del usuario'];
            jsonResponse($response, 400);
            die();
        }

        $intIdUsuario = (int) $_DELETE['id_usuario'];

        require_once __DIR__ . '/../Models/UsuariosModel.php';
        $this->model = new UsuariosModel();

        $request = $this->model->deleteUsuario($intIdUsuario);

        if ($request) {
            $response = [
                'status'     => true,
                'msg'        => 'Usuario eliminado correctamente',
            ];
            jsonResponse($response, 200);
        } else {
            $response = [
                'status' => false,
                'msg'    => 'No se pudo eliminar el usuario.',
            ];
            jsonResponse($response, 500);
        }
        die();

    }

}
