<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PatientCard extends Component
{
    public $name;
    public $type;
    public $status;
    public $appointment;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $type
     * @param string $status
     * @param mixed $appointment
     */
    public function __construct($name, $type, $status, $appointment)
    {
        $this->name = $name;
        $this->type = $type;
        $this->status = $status;
        $this->appointment = $appointment;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.patient-card');
    }
}
