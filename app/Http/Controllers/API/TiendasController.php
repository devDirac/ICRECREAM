<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Tiendas;



/**
 * @OA\Tag(
 *     name="Tiendas",
 *     description="Controla todo lo relacionado con las tiendas"
 * ),
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class TiendasController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/ICECREAM/public/api/get-tiendas",
     *     tags={"Tiendas"},
     *     description="Obtiene todas las tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ {"id": 1, "nombre": "nombre tienda","ubicacion_direccion":"calle tal","lat":"9.231321","lon":"-9.56456", "fecha_registro": "2024-02-29 09:27:54"} }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre tienda"
     *                      ),
     *                      @OA\Property(
     *                         property="ubicacion_direccion",
     *                         type="string",
     *                         example="calle tal"
     *                      ),
     *                      @OA\Property(
     *                         property="lat",
     *                         type="string",
     *                         example="9.5644"
     *                      ),
     *                      @OA\Property(
     *                         property="lon",
     *                         type="string",
     *                         example=".9231231"
     *                      ),
     *                      @OA\Property(
     *                         property="fecha_registro",
     *                         type="string",
     *                         example="2024-02-29 09:27:54"
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
    public function getAll(Request $request)
    {
        try {
           
            $data = Tiendas::all();
            return $this->sendResponse($data);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th, 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/creaTienda",
     *     tags={"Tiendas"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"nombre":"nombre tienda","ubicacion_direccion":"calle tal, numero tal","lat":"9.112121", "lon":"-9.12313"}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre tienda"
     *                      ),
     *                      @OA\Property(
     *                         property="ubicacion_direccion",
     *                         type="string",
     *                         example="ubicacion_direccion..."
     *                      ),
     *                      @OA\Property(
     *                         property="lat",
     *                         type="string",
     *                         example="9.54654"
     *                      ),
     *                      @OA\Property(
     *                         property="lon",
     *                         type="string",
     *                         example="-9.54654"
     *                      ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value="...", summary=""),
     *              @OA\Items(),
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
    public function creaTienda(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'ubicacion_direccion' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Todos los campos son requeridos', $validator->errors());
            }
            $input['nombre'] = $request->nombre;
            $input['ubicacion_direccion'] = $request->ubicacion_direccion;
            $input['lat'] = $request->has('lat') ? $request->lat : null;
            $input['lon'] = $request->has('lon') ? $request->lon : null;
            $tienda = Tiendas::create($input);
            return $this->sendResponse($tienda);
        } catch (\Throwable $th) {
            return $this->sendError('Error al guardar el concepto', $th, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/ICECREAM/public/api/editaTienda",
     *     tags={"Tiendas"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"nombre":"nombre tienda","ubicacion_direccion":"calle tal, numero tal","lat":"9.112121", "lon":"-9.12313", "id":1}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre tienda"
     *                      ),
     *                      @OA\Property(
     *                         property="ubicacion_direccion",
     *                         type="string",
     *                         example="ubicacion_direccion..."
     *                      ),
     *                      @OA\Property(
     *                         property="lat",
     *                         type="string",
     *                         example="9.54654"
     *                      ),
     *                      @OA\Property(
     *                         property="lon",
     *                         type="string",
     *                         example="-9.54654"
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
     *              @OA\Examples(example="result", value="...", summary=""),
     *              @OA\Items(),
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
    public function editaTienda(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' =>'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('El id es requerido', $validator->errors());
            }
            $tienda = Tiendas::where('id', $request->id)->get()->first();
            if (!$tienda) {
                return $this->sendError('La tienda que desea actualizar no existe', null, 500);
            }
            $tienda->nombre = $request->has('nombre') ? $request->nombre : $tienda->nombre;
            $tienda->ubicacion_direccion =$request->has('ubicacion_direccion') ? $request->ubicacion_direccion : $tienda->ubicacion_direccion;
            $tienda->lat = $request->has('lat') ? $request->lat : $tienda->lat;
            $tienda->lon = $request->has('lon') ? $request->lon : $tienda->lon;
            $tienda->save();
            return $this->sendResponse($tienda);
        } catch (\Throwable $th) {
            return $this->sendError('Error al editar la tienda', $th, 500);
        }
    }
}