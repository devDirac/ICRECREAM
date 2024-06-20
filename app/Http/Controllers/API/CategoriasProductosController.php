<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Categorias_productos;



/**
 * @OA\Tag(
 *     name="Categorias productos",
 *     description="Controla todo lo relacionado con las categorias de los productos"
 * ),
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class CategoriasProductosController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/ICECREAM/public/api/get-categorias",
     *     tags={"Categorias productos"},
     *     description="Obtiene todas las categorias productos",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ {"id": 1, "nombre": "nombre paletas/hielo","descripcion":"paletas de hielo sin leche","fecha_registro": "2024-02-29 09:27:54"} }, summary=""),
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
     *                         property="descripcion",
     *                         type="string",
     *                         example="paletas de hielo sin leche"
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
           
            $data = Categorias_productos::all();
            return $this->sendResponse($data);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th, 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/creaCategoria",
     *     tags={"Categorias productos"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"nombre":"nombre tienda","descripcion":"descripcion.."}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre categoria"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="descripcion..."
     *                      )
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
    public function creaCategoria(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'descripcion' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Todos los campos son requeridos', $validator->errors());
            }
            $input['nombre'] = $request->nombre;
            $input['descripcion'] = $request->descripcion;
            $tienda = Categorias_productos::create($input);
            return $this->sendResponse($tienda);
        } catch (\Throwable $th) {
            return $this->sendError('Error al guardar el concepto', $th, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/ICECREAM/public/api/editaCategoria",
     *     tags={"Categorias productos"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"nombre":"nombre tienda","descripcion":"calle tal, numero tal", "id":1}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre tienda"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="descripcion..."
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
    public function editaCategoria(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' =>'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('El id es requerido', $validator->errors());
            }
            $tienda = Categorias_productos::where('id', $request->id)->get()->first();
            if (!$tienda) {
                return $this->sendError('La categoria que desea actualizar no existe', null, 500);
            }
            $tienda->nombre = $request->has('nombre') ? $request->nombre : $tienda->nombre;
            $tienda->descripcion =$request->has('descripcion') ? $request->descripcion : $tienda->descripcion;
            $tienda->save();
            return $this->sendResponse($tienda);
        } catch (\Throwable $th) {
            return $this->sendError('Error al editar la categoria', $th, 500);
        }
    }
}