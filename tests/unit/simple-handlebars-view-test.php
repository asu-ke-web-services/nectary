<?php

namespace Nectary\Tests;

use Nectary\Views\Simple_Handlebars_View;
use Nectary\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * @group handlebars
 * @group view
 */
class Simple_Handlebars_View_Test extends TestCase {
  public function test_will_render_handlebars() {
    $template = '{{test}}';
    $view = new Simple_Handlebars_View(
        false,
        'template_name',
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

  public function test_will_render_conditionals_correctly() {
    $template = "
    {{#if_cond value '==' 2}}
      1
    {{/if_cond}}
    {{#if_cond value '===' 2}}
      2
    {{/if_cond}}
    {{#if_cond value '<=' 2}}
      3
    {{/if_cond}}
    {{#if_cond value '>=' 2}}
      4
    {{/if_cond}}
    {{#if_cond 5 '>' 2}}
      5
    {{/if_cond}}
    {{#if_cond 2 '<' 3}}
      6
    {{/if_cond}}
    {{#if_cond true '&&' true}}
      7
    {{/if_cond}}
    {{#if_cond false '||' true}}
      8
    {{/if_cond}}
    ";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
                'value' => 2
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertContains( '1', $output );
    $this->assertContains( '2', $output );
    $this->assertContains( '3', $output );
    $this->assertContains( '4', $output );
    $this->assertContains( '5', $output );
    $this->assertContains( '6', $output );
    $this->assertContains( '7', $output );
    $this->assertContains( '8', $output );
  }

  public function test_will_not_render_false_conditionals() {
    $template = "{{#if_cond value '==' 2}}{{test}}{{/if_cond}}";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
                'test' => 'sustainability',
                'value' => 4
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( '', $output );
  }

  public function test_will_return_html_special_characters() {
    $template = "{{#html_special_chars test}}";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
                'test' => '<>',
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( '&lt;&gt;', $output );
  }

   public function test_html_special_chars_will_silently_fail() {
    $template = "{{#html_special_chars test}}";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( '', $output );
  }

  public function test_will_strip_tags() {
    $template = "{{#strip_tags test}}";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
                'test' => '<p>Test</p>',
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( 'Test', $output );
  }

  public function test_strip_tags_will_silently_fail() {
    $template = "{{#strip_tags test}}";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( '', $output );
  }

  public function test_config_will_return_configuration_value() {
    $template = "{{#config myKey}}";

    Configuration::get_instance()->set( 'myKey', 'myValue' );

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( 'myValue', $output );
  }

  public function test_config_will_return_default() {
    $template = "{{#config myKeyInvalid myDefault}}";

    $view = new Simple_Handlebars_View(
        false,
        'template_name',
        function( $engine ) use ( $template ) {
          return $engine->render(
              $template,
              [
              ]
          );
        }
    );

    $output = $view->output();

    $this->assertEquals( 'myDefault', $output );
  }
}
