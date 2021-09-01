<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'imagem', 'descricao'];

    public function rules() {
        return [
            'titulo' => 'required',
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
