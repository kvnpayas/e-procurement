<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EnvelopeLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function render(): View
    {
        return view('admin.bidding.envelope.envelope');
    }
}
