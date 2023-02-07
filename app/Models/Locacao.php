<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;
    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id', 
        'carro_id', 
        'data_inicio_periodo', 
        'data_final_previsto_periodo', 
        'data_final_realizado_periodo', 
        'valor_diario', 
        'km_inicial', 
        'km_final'
    ];

    public function rules() {
        return [
            'cliente_id' => 'exists:clientes,id', 
            'carro_id' => 'exists:carros,id', 
            'data_inicio_periodo' => 'required', 
            'data_final_previsto_periodo' => 'required',
            'data_final_realizado_periodo' => 'required', 
            'valor_diario' => 'required', 
            'km_inicial' => 'required',
            'km_final' => 'required',
        ];
    }

    public function feedback() {
        return [
            'cliente_id.exists' => 'O cliente não existe no nosso sistema',
            'carro_id.exists' => 'O carro não existe no nosso sistema',
            'required' => 'Campo obrigatorio'
        ];
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function carro() {
        return $this->belongsTo('App\Models\Carro');
    }
}
