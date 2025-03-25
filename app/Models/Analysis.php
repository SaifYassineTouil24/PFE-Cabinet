<?php

class Analysis extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_analysis');
    }
}
?>
