<?php

namespace Nectary\Views;

/**
 * Simple implementation of the Handlebars_View
 */
class Simple_Handlebars_View extends Handlebars_View {
	protected $template_name;
	protected $callback;

	public function __construct( $view_root, $template_name, $callback, $path_to_views = null ) {
		parent::__construct( $view_root, $path_to_views );
		$this->template_name = $template_name;
		$this->callback      = $callback;
	}

	public function output() {
		$callback = $this->callback;

		return $callback( $this->engine );
	}
}
