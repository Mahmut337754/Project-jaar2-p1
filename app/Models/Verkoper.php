<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verkoper extends Model
{
    use HasFactory;

    protected $table = 'Verkoper';

    protected $fillable = [
        'Naam'
        ,'SpecialeStatus'
        ,'VerkooptSoort'
        ,'StandType'
        ,'Dagen'
        ,'Logo'
        ,'IsActief'
        ,'Opmerking'
        ,'DatumAangemaakt'
        ,'DatumGewijzigd'
    ];

    public $timestamps = false; // Omdat je eigen datumvelden gebruikt
}
