<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
use HasFactory;

protected $table = 'appointments';
protected $fillable = ['appointment_date', 'type', 'status','mutuelle','payement'];

protected $primaryKey = 'ID_RV';



public function patient()
{
    return $this->belongsTo(Patient::class, 'ID_patient');
}

public function analyses()
{
    return $this->belongsToMany(Analysis::class, 'appointment_analyse', 'ID_RV', 'ID_Analyse')->withTimestamps();
}


public function medicaments()
{
    return $this->belongsToMany(Medicament::class, 'appointment_medicament', 'ID_RV', 'ID_Medicament')->withTimestamps();
}
public function case_description() {
    return $this->hasMany(CompteRendu::class, 'id');
}
}


?>
