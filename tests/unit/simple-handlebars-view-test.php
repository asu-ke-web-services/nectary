<?php

namespace Nectary\Tests;

use Nectary\Views\Simple_Handlebars_View;

/**
 * @group handlebars
 * @group view
 */
class Simple_Handlebars_View_Test extends \PHPUnit_Framework_TestCase {
  function test_will_render_handlebars() {
    $template = '{{test}}';
    $view = new Simple_Handlebars_View(
        false,
        '{{ test }}',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
                'test' => 'sustainability'
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( 'sustainability', $output );
  }
}