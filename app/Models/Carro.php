<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;
    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules() {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required|unique:carros,placa,'.$this->id.'',
            'disponivel' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function feedback() {
        return [
            'modelo_id.exists' => 'O modelo não existe no nosso sistema',
            'required' => 'O campo :attribute é obrigatório',
            'placa.unique' => 'A placa já existe',
        ];
    }

    public function modelo() {
        return $this->belongsTo('App\Models\Modelo');
    }
}
