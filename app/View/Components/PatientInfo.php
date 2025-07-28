<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Patient;

class PatientInfo extends Component
{

    public string $name;
    public int $age;
    public string $sex;
    public string $cin;
    public string $phone;
    public string $mutuelle;
    public string $allergies;
    public string $chronic;
    public string $email;
    public string $notes;

    /**
     * Create a new PatientInfo component instance.
     *
     * @param string $name
     * @param int $age
     * @param string $sex
     * @param string $cin
     * @param string $phone
     * @param string $mutuelle
     * @param string $allergies
     * @param string $chronic
     * @param string $email
     * @param string $notes
     */


    public function __construct(
        string $name = '',
        int    $age = 0,
        string $sex = '',
        string $cin = '',
        string $phone = '',
        string $mutuelle = 'Aucune',
        string $allergies = 'Aucune',
        string $chronic='Aucune',
        string $email='Aucune',
        string $notes='Aucune'
    ) {
        $this->name = $name;
        $this->age = abs($age);
        $this->sex = $sex;
        $this->cin = $cin;
        $this->phone = $phone;
        $this->mutuelle = $mutuelle;
        $this->allergies = $allergies;
        $this->chronic = $chronic;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function render()
    {
        return view('components.patient-info');
    }
}

