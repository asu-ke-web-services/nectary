<?php

namespace Nectary\Factories;

use Nectary\Factory;
use Nectary\Services\Excerpt_Service;

/**
 * Factory that provides a programmatic approach to
 * building html. Provides basic building blocks, such
 * as links, headings, text, images, and divs
 *
 * @extends Factory
 */
class Html_Factory extends Factory {
  protected $html;

  public function __construct() {
    $this->html = '';
  }

  public function add_heading( $inner_html, $options = [] ) {
    $this->html .= $this->with_heading( $inner_html, $options );
  }

  public function with_heading( $inner_html, $options = [] ) {
    ensure_default( $options, 'level', 2 );
    ensure_default( $options, 'class', '' );

    $classes = $options['class'];
    $level = $options['level'];

    return "<h{$level} class='{$classes}'>{$inner_html}</h{$level}>";
  }

  public function add_link( $inner_html, $options = [] ) {
    $this->html .= $this->with_link( $inner_html, $options );
  }

  public function with_link( $inner_html, $options = [] ) {
    ensure_default( $options, 'href', '#' );
    ensure_default( $options, 'class', '' );

    $classes = $options['class'];
    $href = $options['href'];

    return "<a class='{$classes}' href='{$href}'>{$inner_html}</a>";
  }

  public function add_text( $inner_html, $options = [] ) {
    $this->html .= $this->with_text( $inner_html, $options );
  }

  public function with_text( $inner_html, $options = [] ) {
    ensure_default( $options, 'class', '' );

    $classes = $options['class'];
    return "<p class='{$classes}'>{$inner_html}</p>";
  }

  public function add_text_excerpt( $inner_html, $options = [] ) {
    $this->html .= $this->with_text_excerpt( $inner_html, $options );
  }

  public function with_text_excerpt( $inner_html, $options = [] ) {
    ensure_default( $options, 'character_limit', '150' );
    ensure_default( $options, 'class', '' );

    $classes = $options['class'];
    $excerpt = ( new Excerpt_Service() )->excerpt(
        $inner_html,
        $options['character_limit']
    );

    return "<p class='{$classes}'>{$excerpt}</p>";
  }

  public function add_image( $src, $options = [] ) {
    $this->html .= $this->with_image( $src, $options );
  }

  public function with_image( $src, $options = [] ) {
    ensure_default( $options, 'class', '' );

    $classes = $options['class'];

    return "<img src='{$src}' class='{$classes}' />";
  }

  public function add_div( $inner_html, $options = [] ) {
    $this->html .= $this->with_div( $inner_html, $options );
  }

  public function with_div( $inner_html, $options = [] ) {
    ensure_default( $options, 'class', '' );

    $classes = $options['class'];

    return "<div class='{$classes}'>{$inner_html}</div>";
  }

  public function build() {
    return $this->html;
  }
}
