<?php

namespace Nectary\Factories\Implementations;

use Nectary\Factories\Implementations\Html_Factory;

/**
 * Lazily create HTML Bootstrap based Carousels
 *
 * Allows you to add slides, classes, and indicators
 *
 * @extends Html_Factory
 */
class Html_Carousel_Factory extends Html_Factory {
	private $slides;
	private $indicators;
	private $id;
	private $classes;
	private $inner_classes;
	private $data_attributes;

	public function __construct() {
		$this->id              = uniqid( 'carousel-', true );
		$this->indicators      = false;
		$this->slides          = [];
		$this->classes         = '';
		$this->inner_classes   = '';
		$this->data_attributes = '';
	}

	public function add_slide( $html ) {
		$this->slides[] = $html;
	}

	public function add_class( $classes ) {
		$this->classes .= ' ' . $classes;
	}

	public function add_data_attributes( $attributes ) {
		$this->data_attributes .= ' ' . $attributes;
	}

	public function add_inner_class( $classes ) {
		$this->inner_classes .= ' ' . $classes;
	}

	public function turn_on_indicators() {
		$this->indicators = true;
	}

	public function build() {
		$html = implode( $this->slides, '' );

		if ( $this->indicators ) {
			$indicators = $this->build_indicators();
		} else {
			$indicators = '';
		}

		return
		"<div id='{$this->id}' class='carousel slide {$this->classes}' {$this->data_attributes} data-ride='carousel'>
			{$this->indicators}
			<div class='carousel-inner {$this->inner_classes}' role='listbox'>
				{$html}
				{$indicators}
			</div>
		</div>";
	}

	private function build_indicators() {
		$number           = count( $this->slides );
		$inner_indicators = '';

		for ( $i = 0; $i < $number; $i++ ) {
			$classes           = ( $i === 0 ) ? 'active' : '';
			$inner_indicators .= "
				<li data-target='#{$this->id}' data-slide-to='{$i}' class='{$classes}'></li>
			";
		}

		return "
			<ol class='carousel-indicators'>
				{$inner_indicators}
			</ol>
		";
	}
}
