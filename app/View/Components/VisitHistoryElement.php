<?php

namespace App\View\Components;

use Illuminate\View\Component;

class VisitHistoryElement extends Component
{
    public String $date;
    public $type;
    public String $payement;
    public int $appointmentId;
    public bool $mutuelle;
    // Nouvelle propriété

    /**
     * Create a new component instance.
     *
     * @param String $date
     * @param  $type
     * @param String $payement
     * @param int $appointmentId
     * @param bool $mutuelle

     *
     */
    public function __construct(String $date, $type, String $payement, int $appointmentId, bool $mutuelle)
    {
        $this->date = $date;
        $this->type = $type;
        $this->payement = $payement;
        $this->appointmentId = $appointmentId;
        $this->mutuelle = $mutuelle;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|String
     */
    public function render()
    {
        return view('components.visit-history-element');
    }
}
