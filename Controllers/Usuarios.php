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
        if (empty($_POST['activo']) || ! testBooleanString($_POST['activo'])) {
            $response = ['status' => false, 'msg' => 'Error en el estado activo'];
            jsonResponse($response, 400);
            die();
        }

        
        $strNombre         = ucwords(strtolower(trim($_POST['nombre'])));
        $strEmail          = strtolower(trim($_POST['email']));
        $strUsername       = strtolower(trim($_POST['username']));
        $strPasswordHash   = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $intRolId          = (int) $_POST['rolid'];
        $boolActivo        = filter_var($_POST['activo'], FILTER_VALIDATE_BOOLEAN);

        
        require_once __DIR__ . '/../Models/UsuariosModel.php';
        $this->model = new UsuariosModel();

        $request = $this->model->setCliente(
            
            $strNombre,
            $strEmail,
            $strUsername,
            $strPasswordHash,
            $intRolId,
            $boolActivo
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

}
