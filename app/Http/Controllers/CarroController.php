<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarroController extends Controller
{

    protected $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carros = array();

        if($request->has('atributos_modelo')) {
            $atributos_modelo = $request->atributos_marca;
            $carros = $this->carro->with('modelo:id,'.$atributos_modelo);
        } else {
            $carros = $this->carro->with('modelo');
        }

        if($request->has('filtro')) {
            $filtros = explode(';', $request->filtro);
            foreach($filtros as $key => $condicao) {
                $condicoes = explode(':', $condicao);
                $carros = $carros->where($condicoes[0], $condicoes[1], $condicoes[2]);
            }
        }

        if($request->has('atributos')) {
            $atributos = $request->atributos;
            $carros = $carros->selectRaw($atributos)->get();
        } else {
            $carros = $carros->get();
        }

        return response()->json($carros, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if($carro === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {
            
            $regrasDinamicas = array();

            foreach($carro->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $carro->feedback());

        } else {
            $request->validate($carro->rules(), $carro->feedback());
        }

        $carro->fill($request->all());

        $carro->save();
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if($carro === null) {
            // return ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'];
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $carro->delete();
        return response()->json(['msg' => 'A modelo foi removida com sucesso!'], 200);
    }
}
