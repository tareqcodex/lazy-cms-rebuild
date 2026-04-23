<?php

namespace Acme\CmsDashboard\View\Components;

use Illuminate\View\Component;

class PostRenderer extends Component
{
    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function render()
    {
        if ($this->post->editor_type === 'builder') {
            return view('cms-dashboard::components.builder-renderer');
        }

        return view('cms-dashboard::components.rich-renderer');
    }
}
