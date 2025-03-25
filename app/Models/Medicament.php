<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    protected $table = 'medicaments';
    protected $primaryKey = 'ID_Medicament';

    protected $fillable = ['name', 'price', 'dosage', 'composition', 'classe_therapeutique', 'Code_ATCv'];

    public function appointments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_medicament', 'ID_Medicament', 'ID_RV')->withTimestamps();
    }
}
