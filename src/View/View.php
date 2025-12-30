<?php

namespace Belur\View;

interface View {
    public function render(string $view): string;
}
