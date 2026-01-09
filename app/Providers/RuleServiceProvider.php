<?php

namespace App\Providers;

use Belur\Providers\ServiceProvider;
use Belur\Validation\Rule;

class RuleServiceProvider implements ServiceProvider {
    public function registerServices(): void {
        Rule::loadDefaultRules();
    }
}