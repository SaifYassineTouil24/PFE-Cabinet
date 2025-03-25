<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    public $name;
    public $role;
    public $profilePicture;

    public function __construct($name = "Guest", $role = "User", $profilePicture = null)
    {
        $this->name = $name;
        $this->role = $role;
        $this->profilePicture = $profilePicture ?? 'https://randomuser.me/api/portraits/men/1.jpg';
    }

    public function render()
    {
        return view('components.header');
    }
}
