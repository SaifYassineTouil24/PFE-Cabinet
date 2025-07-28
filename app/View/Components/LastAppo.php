<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LastAppo extends Component
{
    public String $date;
    public  $type;
    public  String $diagnostic;


    /**
     * Create a new component instance.
     *
     * @param String $date
     * @param  $type
     * @param  String $diagnostic
     *
     */
    public function __construct(String $date, $type, String $diagnostic)
    {
        $this->date = $date;
        $this->type = $type;
        $this->diagnostic = $diagnostic;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|String
     */
    public function render()
    {
        return view('components.last-appo');
    }
}
