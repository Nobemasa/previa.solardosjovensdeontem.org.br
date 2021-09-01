<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'zap', 'imagem', 'descricao'];

    public function rules() {
        return [
            'nome' => 'required',
            'imagem' => 'required|file|mimes:png,jpg,jpeg'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'imagem.mimes' => 'É aceito apenas arquivos .png, .jpg ou .jpeg!'
        ];
    }
}
