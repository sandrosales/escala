<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantao extends Model
{
    use HasFactory;

    protected $table = 'plantoes';


    protected $fillable = ['funcionario_id', 'tipo', 'data', 'horario_inicio', 'horario_fim'];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}
