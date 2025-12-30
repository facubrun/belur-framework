<?php

namespace Belur\View;

class BelurEngine implements View {
    protected string $viewsPath;
    protected string $defaultLayout = 'main';
    protected string $contentTag = '@content';
    
    public function __construct(string $viewsPath) {
        $this->viewsPath = $viewsPath;
    }

    public function render(string $view, array $params = [], ?string $layout = null): string {
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);
        $viewContent = $this->renderView($view, $params);

        return str_replace($this->contentTag, $viewContent, $layoutContent);
    }

    protected function renderView(string $view, array $params = []): string {
        return $this->phpFileOutput("{$this->viewsPath}/{$view}.php", $params);
    }

    protected function renderLayout(string $layout): string {
        return $this->phpFileOutput("{$this->viewsPath}/layouts/{$layout}.php");
    }

    protected function phpFileOutput(string $phpFile, array $params = []): string {
        foreach ($params as $param => $value) {
            $$param = $value;
        }
        ob_start();
        include_once $phpFile;
        return ob_get_clean();
    }

}
