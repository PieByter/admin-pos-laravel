<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContentHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public string $title;
    public string $breadcrumbParent;
    public string $breadcrumbUrl;

    public function __construct($title = '', $breadcrumbParent = '', $breadcrumbUrl = '#')
    {
        $this->title = $title;
        $this->breadcrumbParent = $breadcrumbParent;
        $this->breadcrumbUrl = $breadcrumbUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.content-header');
    }
}
