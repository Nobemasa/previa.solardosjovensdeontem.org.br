<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['titulo', 'link'];

    public function rules() {
        return [
            'titulo' => 'required|min:4',
            'link' => 'required|min:4'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'titulo.min' => 'É necessário 4 caracteres no mínimo!',
            'link.min' => 'É necessário 4 caracteres no mínimo!'
        ];
    }
}
