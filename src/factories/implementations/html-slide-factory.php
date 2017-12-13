<?php

namespace Nectary\Factories;

/**
 * Create Html Slides for Html Carousels
 *
 * Allows you to set the inner html, classes
 * and whether the slide is active or not
 *
 * @extends Html_Factory
 */
class Html_Slide_Factory extends Html_Factory {
	private $active;
	private $classes;

	public function __construct() {
		$this->active  = false;
		$this->classes = '';
	}

	public function is_active() {
		$this->active = true;
	}

	public function add_class( $classes ) {
		$this->classes .= $classes;
	}

	public function build() {
		$classes = ( $this->active ) ? 'active' : '';

		$classes .= ' ' . $this->classes;

		return "<div class='item {$classes}'>
			{$this->html}
		</div>";
	}
}
