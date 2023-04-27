<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PendinController extends Component
{

    public $cart;

    protected $listeners = ['ScanCode'];

    public function ScanCode(){
        
    }

    public function render()
    {
        return view('livewire.pendin.component');
    }
}
