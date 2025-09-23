<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verkoper extends Model
{
    use HasFactory;

    // Verwijst naar de database-tabel 'Verkoper'
    protected $table = 'Verkoper';

    // Velden die ingevuld mogen worden
    protected $fillable = [
        'Naam',
        'SpecialeStatus',
        'VerkooptSoort',
        'StandType',
        'Dagen',
        'Logo',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd'
    ];
    public $timestamps = false;
}
