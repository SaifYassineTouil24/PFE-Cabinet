<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'ID_patient';

    protected $fillable = ['name', 'birth_day', 'gender', 'CIN', 'phone_num', 'mutuelle','allergies','chronic_conditions'];

    public function Appointment()
    {
        return $this->hasMany(Appointment::class, 'ID_patient');
    }

    public function certificats()
    {
        return $this->hasMany(\Certificate::class, 'ID_patient');
    }
}

?>
