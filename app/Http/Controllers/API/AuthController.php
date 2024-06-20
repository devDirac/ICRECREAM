<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Utils\MailSend;
use Illuminate\Support\Facades\DB;
use App\Models\Tokens;
use App\Models\ProcesosTokens;

/**
 * @OA\Tag(
 *     name="Autenticacion",
 *     description="Controlador para administrar lo relacionado con el manejo de sesiones"
 * ),
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class AuthController extends BaseController
{

    public $mailValidation = 'required|email';
    public $invalidFormatMessage = 'Formato invalido';

    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/register",
     *     tags={"Autenticacion"},
     *     security={{"bearer_token":{}}},
     *     description="Crea un nuevo usuario para el inicio de sesion",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"name":"name","usuario":"superadmin", "email": "juan.perez@correo.com", "password": "12345", "telefono":"5635544556", "foto":"base64:Asadsada" }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="name"
     *                      ),
     *                      @OA\Property(
     *                         property="usuario",
     *                         type="string",
     *                         example="superadmin"
     *                      ),
     *                      @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="juan.perez@correo.com"
     *                      ),
     *                      @OA\Property(
     *                         property="password",
     *                         type="string",
     *                         example="12345"
     *                      ),
     *                      @OA\Property(
     *                         property="telefono",
     *                         type="string",
     *                         example="56889878"
     *                      ),
     *                      @OA\Property(
     *                         property="foto",
     *                         type="string",
     *                         example="12345"
     *                      )
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={  "id_empleado":"correo.persona","name": "name", "usuario":"xxxx",
     *                                                      "email": "correo.persona@correo.com", "updated_at": "2024-03-07T14:34:40.000000Z", 
     *                                                      "created_at": "2024-03-07T14:34:40.000000Z",
     *                                                      "id": 12 }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=0
     *                      ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="El correo ingresado ya fue dado de alta anteriormente",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Examples(example="result", value={ "success": false,
     *                                                      "message": "El correo ingresado ya fue dado de alta anteriormente",
     *                                                      "data": { "errorInfo": { "23000",
     *                                                                  1062, 
     *                                                      "Duplicate entry 'juan.perez@correo.com' for key 'users_email_unique'" }
     *                                                   }}, summary=""),
     *         ),
     *     ),
     *  )
     * )
     */
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'usuario' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($this->invalidFormatMessage, $validator->errors());
            }
            $exist = User::where('usuario', $request->usuario)->get()->first();
            if ($exist) {
                return $this->sendError('El usuario ingresado ya fue dado de alta anteriormente', [], 500);
            }
            $input1['name'] = $request->name;
            $input1['email'] = $request->email;
            $input1['password'] = bcrypt($request->password);
            $input1['usuario'] = $request->usuario;
            $input1['password_sinCifrar'] = $request->password;
            $input1['telefono'] = $request->has('telefono') ? $request->telefono : null;
            $input1['foto'] = $request->has('foto') ? $request->foto : '';
            $input1['activo'] = 1;
            $user = User::create($input1);
            return $this->sendResponse($user);
        } catch (\Throwable $th) {
            return $this->sendError('El correo ingresado ya fue dado de alta anteriormente', $th, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/login",
     *     tags={"Autenticacion"},
     *     description="Inicia sesion",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"email":"moy@correo.com", "password": "admin123"}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="moy@correo.com"
     *                      ),
     *                      @OA\Property(
     *                         property="password",
     *                         type="string",
     *                         example="admin123"
     *                      ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ "data": { "id": 10, "name": "sergio", "email": "moy@correo.com@correo.com", "created_at": "2024-02-27T20:18:28.000000Z","updated_at": "2024-02-27T20:18:28.000000Z" }, "token": "48|kt8V1hozjXc2GoD0iM1il5YO7hNezcbF19RodZsj45f110e9" }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=0
     *                      ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Fallo en inicio de sesion",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="la contraseña o el correo electronico son incorrectos", summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *  )
     * )
     */
    public function signin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($this->invalidFormatMessage, $validator->errors());
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'activo' => 1])) {
                $user = Auth::user();
                $users['data'] = $user;
                $token = $user->createToken('MyAuthApp');
                $users['token'] = $token->plainTextToken;
                unset($user->password_sinCifrar);
                unset($user->created_at);
                unset($user->updated_at);
                return $this->sendResponse($users);
            } else {
                return $this->sendError('La contraseña o el usuario son incorrectos o el usuario ya fue dado de baja', ['error' => ''], 401);
            }
        } catch (\Throwable $th) {
            return $this->sendError('Error al iniciar sesión', $th, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/ICECREAM/public/api/getUserRefrsh/{id}",
     *     tags={"Autenticacion"},
     *     description="Refresca la sesion del usuario",
     *     security={{"bearer_token":{}}},
     *  @OA\Parameter(
     *      name="id",
     *      description="Id del usuario que inicio sesion",
     *      example=1,
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={  "id": 10, 
     *                                                      "name": "moy", 
     *                                                      "email": "moy@correo.com",
     *                                                      "created_at": "2024-02-27T20:18:28.000000Z",
     *                                                      "updated_at": "2024-02-27T20:18:28.000000Z", "token": "11" }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=0
     *                      ),
     *                      @OA\Property(
     *                         property="fecha",
     *                         type="string",
     *                         example=0
     *                      ),
     *                      @OA\Property(
     *                         property="titulo",
     *                         type="string",
     *                         example="titulo"
     *                      ),
     *                      @OA\Property(
     *                         property="scriptSQL",
     *                         type="string",
     *                         example="select * from chart_1;"
     *                      ),
     *                      @OA\Property(
     *                         property="esVertical",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="esApilado",
     *                         type="number",
     *                         example=0
     *                      ),
     *                      @OA\Property(
     *                         property="rellenaEspacioEnLineal",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="tipoGrafica",
     *                         type="string",
     *                         example="barras"
     *                      ),
     *                      @OA\Property(
     *                         property="size",
     *                         type="string",
     *                         example="6"
     *                      ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario fuera de sesion",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Examples(example="result", value={"message": "Unauthenticated."}, summary=""),
     *         ),
     *     ),
     *  )
     * )
     */
    public function getUserRefrsh(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $users['data'] = $user;
            $users['token'] = $request->token;
            unset($user->password_sinCifrar);
            unset($user->created_at);
            unset($user->updated_at);
            return $this->sendResponse($users);
        } catch (\Throwable $th) {
            return $this->sendError('Error al recuperar los datos del usuario', $th, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/logOut",
     *     tags={"Autenticacion"},
     *     description="Cerrar sesion",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="Cierre de sesión exitoso.", summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=0
     *                      ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario fuera de sesion",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Examples(example="result", value={"message": "Unauthenticated."}, summary=""),
     *         ),
     *     ),
     *  )
     * )
     */
    public function logOut(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            Auth::guard('web')->logout();
            return $this->sendResponse('Cierre de sesión exitoso.');
        } catch (\Throwable $th) {
            return $this->sendError('Error al cerrar la sesión del usuario', $th, 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/recuperaContrasena",
     *     tags={"Autenticacion"},
     *     description="Inicia proceso para recuperar contraseña",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"correo":"moy@correo.com"}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="correo",
     *                         type="string",
     *                         example="moy@correo.com"
     *                      )
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="exito al enviar correo", summary=""),
     *              @OA\Items(),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Correo no registrado",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ "success": false, "message": "Error", "data": "Cuenta de correo no registrada"}, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=409,
     *         description="Parametro correo es requerido",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "El correo es requerido", "data": { "correo": {{"The correo field is required."}}   }}, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="En proceso",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "erro", "data": "Ya tienes un proceso referente a esta solicitud, verifica tu corre si no lo encuentras revisa tu carpeta de spam, aproximadamente este proceso se puede volver a realizar cada 24 hrs" }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *  )
     * )
     */
    public function passwordRecoverSendLink(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'correo' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('El correo es requerido', $validator->errors(), 409);
            }
            $user = User::where('email', $input['correo'])->get()->first();
            if (!$user) {
                return $this->sendError('Cuenta de correo no registrada', 'error', 404);
            }
            $procesoId = ProcesosTokens::where('proceso', 'recuperar password')->get()->first();

            $tokenValidation = Tokens::where('id_token_proceso', $procesoId->id)->where('id_usuario', $user->id)->get()->first();
            if ($tokenValidation) {
                return $this->sendError('Ya tienes un proceso referente a esta solicitud, verifica tu correo, si no lo encuentras revisa tu carpeta de spam, aproximadamente este proceso se puede volver a realizar cada 24 horas', "error", 500);
            }
            $token = bcrypt($user->email . $user->id);

            $tokenAdd['token'] = $token;
            $tokenAdd['id_usuario'] = $user->id;
            $tokenAdd['id_token_proceso'] = $procesoId->id;
            $user = Tokens::create($tokenAdd);



            $sendMail = new MailSend();
            $mail = $sendMail->sendMailPro([
                'email' => $input['correo'],
                'titulo' => '<h1 style="color:#F89E44; font-family: var(--bs-font-sans-serif);">' . $user->name . '</h1><h3 style="color:#38425d; font-weight: bold; font-family: var(--bs-font-sans-serif);">ICECREAM</h3>',// "Hola ".$user->name,
                'html' => '<h3 style="color:#38425d; font-weight: bold; font-family: var(--bs-font-sans-serif);">Haz iniciado el proceso para la recuperación de tu contraseña' . "<br>" . 'para reestablecer tu contraseña da click ' . "<br>" . '<a href="http://localhost:3000/recupera-password-validacion?token=' . $token . '">aqui</a></h3><br><br><br><p style="color:#38425d; font-weight: bold; font-family: var(--bs-font-sans-serif);">Tienes un plazo de 24hrs para reestablecer tu conmtraseña</p>',
            ], 'mail', "Recuperación de contraseña");

            return $mail;
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/recuperaContrasenaTokenValidacion",
     *     tags={"Autenticacion"},
     *     description="valida que el token sea valido",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"token":"xxxxxxxxxxxxxxxxx"}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="token",
     *                         type="string",
     *                         example="xxxxxxxx"
     *                      )
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="exito al validar el token", summary=""),
     *              @OA\Items(),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="El parametro token no fue proporcionado",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ "success": false, "message": "El token es requerido", "data": ""}, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Este token no es valido o ya fue utilizado",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "Este token no es valido o ya fue utilizado", "data": ""  }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="En proceso",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "erro", "data": "Error" }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *  )
     * )
     */
    public function passwordRecoverTokenValidation(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'token' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('El token es requerido', $validator->errors(), 409);
            }
            $tokenValidation = Tokens::where('token', $input['token'])->get()->first();
            if (!$tokenValidation) {
                return $this->sendError('Este token no es válido o ya fue utilizado', "error", 400);
            }
            return $this->sendResponse($tokenValidation);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/actualizacionContrasena",
     *     tags={"Autenticacion"},
     *     description="Realiza la actualización del password",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"contrasena":"xxxx","contrasenaConfirm":"xxxx","token":"xxxxxxxxxxxxxxxxx"}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="token",
     *                         type="string",
     *                         example="xxxxxxxx"
     *                      ),
     *                      @OA\Property(
     *                         property="contrasena",
     *                         type="string",
     *                         example="xxxxxxxx"
     *                      ),
     *                      @OA\Property(
     *                         property="contrasenaConfirm",
     *                         type="string",
     *                         example="xxxxxxxx"
     *                      ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="Se ha actualizado la contraseña con exito.", summary=""),
     *              @OA\Items(),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="El password, la confirmación del password y el token son requeridos",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ "success": false, "message": "El password, la confirmación del password y el token son requeridos", "data": ""}, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="El password, la confirmación del password no son iguales",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "El password, la confirmación del password no son iguales", "data": ""  }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="No existe relacion del token con el usuario",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "No existe relacion del token con el usuario", "data": ""  }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="En proceso",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "erro", "data": "Error" }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *  )
     * )
     */
    public function passwordReset(Request $request, User $usuario)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'contrasena' => 'required',
                'contrasenaConfirm' => 'required',
                'token' => 'required'

            ]);
            if ($validator->fails()) {
                return $this->sendError('La contraseña, la confirmación de la contraseña y el token son requeridos', $validator->errors(), 409);
            }
            if ($input['contrasena'] !== $input['contrasenaConfirm']) {
                return $this->sendError('La contraseña y la confirmación de la contraseña no son iguales', $validator->errors(), 400);
            }

            $infoTokenUser = DB::table('tokens')
                ->join('users', 'users.id', '=', 'tokens.id_usuario')
                ->select('tokens.id', 'tokens.token', 'tokens.id_usuario', 'users.name', 'users.email')
                ->where('tokens.token', $input['token'])->get()->first();

            if (!$infoTokenUser) {
                return $this->sendError('No existe relación del token con el usuario', [], 404);
            }

            $update['password'] = bcrypt($input['contrasena']);
            $usuario->where('id', '=', $infoTokenUser->id_usuario)->update($update);

            DB::table('tokens')->where('token', $input['token'])->delete();

            return $this->sendResponse('Se ha actualizado la contraseña con éxito.');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/ICECREAM/public/api/editUser",
     *     tags={"Autenticacion"},
     *     security={{"bearer_token":{}}},
     *     description="Realiza la actualización de un usuario",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"name":"name","usuario":"superadmin", "email": "juan.perez@correo.com", "telefono":"5635544556", "foto":"base64:Asadsada",  "id":1}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="name"
     *                      ),
     *                      @OA\Property(
     *                         property="usuario",
     *                         type="string",
     *                         example="superadmin"
     *                      ),
     *                      @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="juan.perez@correo.com"
     *                      ),
     *                      @OA\Property(
     *                         property="password",
     *                         type="string",
     *                         example="12345"
     *                      ),
     *                      @OA\Property(
     *                         property="telefono",
     *                         type="string",
     *                         example="56889878"
     *                      ),
     *                      @OA\Property(
     *                         property="foto",
     *                         type="string",
     *                         example="12345"
     *                      ),
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=1
     *                      ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="Se ha actualizado el usuario con exito", summary=""),
     *              @OA\Items(),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Este usuario no existe",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "Este usuario no existe", "data": ""  }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="En proceso",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "erro", "data": "Error" }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *  )
     * )
     */
    public function editUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'usuario' => 'required',
                'telefono' => 'required',
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($this->invalidFormatMessage, $validator->errors());
            }
            $user = User::find($request->id);
            if (!$user) {
                return $this->sendError('Este usuario no existe', [], 404);
            }
            $user->name = $request->name;
            $user->usuario = $request->usuario;
            $user->telefono = $request->telefono;
            $user->foto = $request->has('foto') ? $request->foto : '';
           
            $user->save();
            return $this->sendResponse("Se ha actualizado el usuario con éxito");
        } catch (\Throwable $th) {
            return $this->sendError('El correo ingresado ya fue dado de alta anteriormente', $th, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/ICECREAM/public/api/setActiveUser",
     *     tags={"Autenticacion"},
     *     security={{"bearer_token":{}}},
     *     description="Realiza la actualización del estatus de un usuario",
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"id":1}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=1
     *                      ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="Se ha actualizado el usuario con exito", summary=""),
     *              @OA\Items(),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Este usuario no existe",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "Este usuario no existe", "data": ""  }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="En proceso",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={"success": false, "message": "erro", "data": "Error" }, summary=""),
     *              @OA\Items(
     *              ),
     *         ),
     *     ),
     *  )
     * )
     */
    public function setActiveUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError($this->invalidFormatMessage, $validator->errors());
            }
            $user = User::find($request->id);
            if (!$user) {
                return $this->sendError('Este usuario no existe', 'error', 404);
            }
            $user->activo = !$user->activo;
            $user->save();

            return $this->sendResponse($user);
        } catch (\Throwable $th) {
            return $this->sendError('El correo ingresado ya fue dado de alta anteriormente', $th, 500);
        }
    }

    

}
