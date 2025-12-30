<?php

namespace Belur\View;

class BelurEngine implements View {
    protected string $viewsPath;
    
    public function __construct(string $viewsPath) {
        $this->viewsPath = $viewsPath;
    }

    public function render(string $view): string {
        $phpFile = "{$this->viewsPath}/{$view}.php";

        ob_start();
        include_once $phpFile;
        return ob_get_clean();
    }
}
