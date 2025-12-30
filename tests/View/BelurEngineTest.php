<?php

namespace Belur\Tests\View;

use Belur\View\BelurEngine;
use PHPUnit\Framework\TestCase;

class BelurEngineTest extends TestCase {

    public function test_renders_template_with_parameters() {
        $parameter1 = 'Testeo 1';
        $parameter2 = 2;
    
        $expected =
"<html>
    <body>
        <h1>Testeo 1</h1>
        <h1>2</h1>
    </body>
</html>";
        
        $engine = new BelurEngine(__DIR__ . '/views');
        $content = $engine->render('test', compact('parameter1', 'parameter2'), 'layout');

        $this->assertEquals(
            preg_replace('/\s*/', '', $expected),
            preg_replace('/\s*/', '', $content)
        );

    }
}
