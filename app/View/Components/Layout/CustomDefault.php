<?php

namespace App\View\Components\Layout;

use Illuminate\View\Component;

class CustomDefault extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ?string $title = null)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.layout.custom-default');
    }
}
