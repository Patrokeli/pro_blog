<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Edit extends Component
{
    public function __invoke()
    {
        return $this->render();
    }

    public function render()
    {
        return view('livewire.profile.edit');
    }
}
