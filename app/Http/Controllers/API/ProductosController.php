<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Productos;


/**
 * @OA\Tag(
 *     name="Productos",
 *     description="Controla todo lo con los productos"
 * ),
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class ProductosController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/ICECREAM/public/api/get-all-productos",
     *     tags={"Productos"},
     *     description="Obtiene todas los Productos",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ {
     *                              "id": 1, 
     *                              "nombre": "nombre paletas/hielo",
     *                              "descripcion":"paletas de hielo sin leche",
     *                              "precio":15.00,
     *                              "cantidad":100,
     *                              "vendidos":2,
     *                              "id_tienda":1,
     *                              "id_categoria":1,
     *                              "id_usuario_venta":1,
     *                              "id_usuario_creacion":1,
     *                              "fecha_venta":null,
     *                              "fecha_registro": "2024-02-29 09:27:54",
     *                              "tienda": "general",
     *                              "ubicacion_tienda": "xxxxx",
     *                              "latitud_tienda": "9.2344",
     *                              "longitud_tienda": "9.2344",
     *                              "categoria": "paletas / hielo",
     *                              "descripcion_categoria":"xxxxx"
     *                } }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre paletas/hielo"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="paletas de hielo sin leche"
     *                      ),
     *                      @OA\Property(
     *                         property="precio",
     *                         type="number",
     *                         example=12.15
     *                      ),
     *                      @OA\Property(
     *                         property="cantidad",
     *                         type="number",
     *                         example=100
     *                      ),
     *                      @OA\Property(
     *                         property="vendidos",
     *                         type="number",
     *                         example=3
     *                      ),
     *                      @OA\Property(
     *                         property="id_tienda",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_categoria",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_usuario_venta",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_usuario_creacion",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="fecha_venta",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="tienda",
     *                         type="string",
     *                         example="general"
     *                      ),
     *                      @OA\Property(
     *                         property="ubicacion_tienda",
     *                         type="string",
     *                         example="xxxxx"
     *                      ),
     *                      @OA\Property(
     *                         property="latitud_tienda",
     *                         type="string",
     *                         example="9.2344"
     *                      ),
     *                      @OA\Property(
     *                         property="longitud_tienda",
     *                         type="string",
     *                         example="-9.2344"
     *                      ),
     *                      @OA\Property(
     *                         property="categoria",
     *                         type="string",
     *                         example="paletas / hielo"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion_categoria",
     *                         type="string",
     *                         example="xxxxx"
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
    public function getProductos()
    {
        try {
            $data = DB::select("select a.*, b.nombre as tienda, b.ubicacion_direccion as ubicacion_tienda, b.lat as latitud_tienda, b.lon as longitud_tienda, c.nombre as categoria, c.descripcion as descripcion_categoria
                                from productos a 
                                inner join tiendas b on a.id_tienda = b.id
                                inner join categorias_productos c on a.id_categoria = c.id", []);
            return $this->sendResponse($data);
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/get-all-productos-fecha",
     *     tags={"Productos"},
     *     description="Obtiene todas los Productos",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={"fechaInicio":"2024-02-29","fechaFin":"2024-02-29"}, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="fechaInicio",
     *                         type="string",
     *                         example="2024-02-29"
     *                      ),
     *                      @OA\Property(
     *                         property="fechaFin",
     *                         type="string",
     *                         example="2024-02-29"
     *                      )
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *              @OA\Examples(example="result", value={ {
     *                              "id": 1, 
     *                              "nombre": "nombre paletas/hielo",
     *                              "descripcion":"paletas de hielo sin leche",
     *                              "precio":15.00,
     *                              "cantidad":100,
     *                              "vendidos":2,
     *                              "id_tienda":1,
     *                              "id_categoria":1,
     *                              "id_usuario_venta":1,
     *                              "id_usuario_creacion":1,
     *                              "fecha_venta":null,
     *                              "fecha_registro": "2024-02-29 09:27:54",
     *                              "tienda": "general",
     *                              "ubicacion_tienda": "xxxxx",
     *                              "latitud_tienda": "9.2344",
     *                              "longitud_tienda": "9.2344",
     *                              "categoria": "paletas / hielo",
     *                              "descripcion_categoria":"xxxxx"
     * } }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre paletas/hielo"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="paletas de hielo sin leche"
     *                      ),
     *                      @OA\Property(
     *                         property="precio",
     *                         type="number",
     *                         example=12.15
     *                      ),
     *                      @OA\Property(
     *                         property="cantidad",
     *                         type="number",
     *                         example=100
     *                      ),
     *                      @OA\Property(
     *                         property="vendidos",
     *                         type="number",
     *                         example=3
     *                      ),
     *                      @OA\Property(
     *                         property="id_tienda",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_categoria",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_usuario_venta",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_usuario_creacion",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="fecha_venta",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="tienda",
     *                         type="string",
     *                         example="general"
     *                      ),
     *                      @OA\Property(
     *                         property="ubicacion_tienda",
     *                         type="string",
     *                         example="xxxxx"
     *                      ),
     *                      @OA\Property(
     *                         property="latitud_tienda",
     *                         type="string",
     *                         example="9.2344"
     *                      ),
     *                      @OA\Property(
     *                         property="longitud_tienda",
     *                         type="string",
     *                         example="-9.2344"
     *                      ),
     *                      @OA\Property(
     *                         property="categoria",
     *                         type="string",
     *                         example="paletas / hielo"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion_categoria",
     *                         type="string",
     *                         example="xxxxx"
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
    public function getProductosPorFecha(Request $request)
    {
        try {
            $responses = new BaseController();
            $query_fechas = '';
            if ($request->has('fechaInicio') && $request->has('fechaFin')) {
                $query_fechas .= " where date(a.fecha_registro) BETWEEN '" . $request->fechaInicio . "' AND '" . $request->fechaFin . "'";
            }
            if (!$request->has('fechaInicio') && $request->has('fechaFin')) {
                $query_fechas .= " where date(a.fecha_registro) <= '" . $request->fechaFin . "'";
            }
            if ($request->has('fechaInicio') && !$request->has('fechaFin')) {
                $query_fechas .= " where date(a.fecha_registro) >= '" . $request->fechaInicio . "'";
            }
            $data = DB::select("select a.*, b.nombre as tienda, b.ubicacion_direccion as ubicacion_tienda, b.lat as latitud_tienda, b.lon as longitud_tienda, c.nombre as categoria, c.descripcion as descripcion_categoria
                                from productos a 
                                inner join tiendas b on a.id_tienda = b.id
                                inner join categorias_productos c on a.id_categoria = c.id  " . $query_fechas . " ", []);
            return $responses->sendResponse("select a.*, b.nombre as tienda, b.ubicacion_direccion as ubicacion_tienda, b.lat as latitud_tienda, b.lon as longitud_tienda, c.nombre as categoria, c.descripcion as descripcion_categoria
                                from productos a 
                                inner join tiendas b on a.id_tienda = b.id
                                inner join categorias_productos c on a.id_categoria = c.id  " . $query_fechas . " ");
        } catch (\Throwable $th) {
            return $responses->sendError('Error', $th, 500);
        }
    }

     /**
     * @OA\Post(
     *     path="/ICECREAM/public/api/creaProducto",
     *     tags={"Productos"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={ 
     *                                  "nombre":"nombre",
     *                                  "descripcion":"descripcion",
     *                                  "precio":10,
     *                                  "cantidad":100,
     *                                  "vendidos":0,
     *                                  "id_tienda":1,
     *                                  "id_categoria":1
     *              }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="descripcion..."
     *                      ),
     *                      @OA\Property(
     *                         property="precio",
     *                         type="number",
     *                         example=15
     *                      ),
     *                      @OA\Property(
     *                         property="cantidad",
     *                         type="number",
     *                         example=100
     *                      ),
     *                      @OA\Property(
     *                         property="vendidos",
     *                         type="number",
     *                         example=0
     *                      ),
     *                      @OA\Property(
     *                         property="id_tienda",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_categoria",
     *                         type="number",
     *                         example=1
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
    public function creaProducto(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'descripcion' => 'required',
                'precio' => 'required',
                'cantidad' => 'required',
                'id_tienda' => 'required',
                'id_categoria' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Todos los campos son requeridos', $validator->errors());
            }
            $user = Auth::user();
            $input['id_usuario_creacion'] = $user->id;
            $input['nombre'] = $request->nombre;
            $input['descripcion'] = $request->descripcion;
            $input['precio'] = $request->precio;
            $input['cantidad'] = $request->cantidad;
            $input['id_tienda'] = $request->id_tienda;
            $input['id_categoria'] = $request->id_categoria;
            $producto = Productos::create($input);
            return $this->sendResponse($producto);
        } catch (\Throwable $th) {
            return $this->sendError('Error al guardar el producto', $th, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/ICECREAM/public/api/editaProducto",
     *     tags={"Productos"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={ 
     *                                  "nombre":"nombre",
     *                                  "descripcion":"descripcion",
     *                                  "precio":10,
     *                                  "cantidad":100,
     *                                  "vendidos":0,
     *                                  "id_tienda":1,
     *                                  "id_categoria":1,
     *                                   "id":1
     *              }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="nombre",
     *                         type="string",
     *                         example="nombre"
     *                      ),
     *                      @OA\Property(
     *                         property="descripcion",
     *                         type="string",
     *                         example="descripcion..."
     *                      ),
     *                      @OA\Property(
     *                         property="precio",
     *                         type="number",
     *                         example=15
     *                      ),
     *                      @OA\Property(
     *                         property="cantidad",
     *                         type="number",
     *                         example=100
     *                      ),
     *                      @OA\Property(
     *                         property="vendidos",
     *                         type="number",
     *                         example=0
     *                      ),
     *                      @OA\Property(
     *                         property="id_tienda",
     *                         type="number",
     *                         example=1
     *                      ),
     *                      @OA\Property(
     *                         property="id_categoria",
     *                         type="number",
     *                         example=1
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
    public function editaProducto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Todos los campos son requeridos', $validator->errors());
            }
            $producto = Productos::where('id', $request->id)->get()->first();
            if (!$producto) {
                return $this->sendError('El producto que desea actualizar no existe', null, 500);
            }
            $producto->nombre = $request->has('nombre') ? $request->nombre : $producto->nombre;
            $producto->descripcion = $request->has('descripcion') ? $request->descripcion : $producto->descripcion;
            $producto->precio = $request->has('precio') ? $request->precio : $producto->precio;
            $producto->cantidad = $request->has('cantidad') ? $request->cantidad : $producto->cantidad;
            $producto->id_tienda = $request->has('id_tienda') ? $request->id_tienda : $producto->id_tienda;
            $producto->id_categoria = $request->has('id_categoria') ? $request->id_categoria : $producto->id_categoria;
            $producto->save();
            return $this->sendResponse($producto);
        } catch (\Throwable $th) {
            return $this->sendError('Error al guardar el producto', $th, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/ICECREAM/public/api/vendeProducto",
     *     tags={"Productos"},
     *     description="Guarda tiendas",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="El cuerpo del body debe de tener la siguiente estructura",
     *         required=true,
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Examples(example="result", value={ 
     *                                  "id":1,
     *                                  "cantidad_ventas":1
     *              }, summary=""),
     *              @OA\Items(
     *                      @OA\Property(
     *                         property="cantidad_ventas",
     *                         type="number",
     *                         example=1
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
    public function vendeProducto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'cantidad_ventas' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Todos los campos son requeridos', $validator->errors());
            }
            $producto = Productos::where('id', $request->id)->get()->first();
            if (!$producto) {
                return $this->sendError('El producto que desea actualizar no existe', null, 500);
            }
            $user = Auth::user();
            $producto->fecha_venta = now();
            $producto->id_usuario_venta = $user->id;
            $producto->vendidos = $request->cantidad_ventas;
            $producto->save();
            return $this->sendResponse($producto);
        } catch (\Throwable $th) {
            return $this->sendError('Error al guardar el producto', $th, 500);
        }
    }

}