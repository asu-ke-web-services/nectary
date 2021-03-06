<?php

namespace Nectary;

use \Nectary\Factories\Dependency_Injection_Factory;

/**
 * The base presenter has functionality to route
 * undefined methods to other class methods.
 *
 * In order to make use of this functionality, simply
 * define a `protected $routes` in your class that
 * implements this abstract class.
 *
 * Routes are expected to have:
 * - to : The Class and Method to route to.
 * - expects : The arguments expected by this route.
 *             Order is important, incoming arguments
 *             are named in this order.
 * - on_error : If a Request has a validation error
 *              it will call this function with a $message,
 *              and $this.
 *
 * Example:
 *
 * ```
 * $present = new Events_Presenter();
 * $present->get_blurb_list( [ 'slug' ], 2005 );
 * ```
 *
 * Will get mapped to:
 *
 * ```
 * array(
 *   'category_slugs' => [ 'slugs' ],
 *   'year' => 2005
 * )
 * ```
 *
 * When given the following route:
 *
 * ```
 * protected $routes = [
 *   'get_blurb_list' => [
 *    'to'       => '\Gios_Api\Events_Controller@get_blurb_list',
 *     'expects'  => [ 'category_slugs', 'year', 'base_path' ],
 *     'on_error' => [ 'Events_Presenter', 'error_callback' ],
 *   ],
 * ];
 * ```
 *
 * Purposefully not in the Gios_Api namespace.
 */
abstract class Router {
	private $__method_name;
	private $__arguments;

	/**
	 * Route calls to undefined methods if the method
	 * has been told to route to a different method
	 *
	 * @param  string $method_name The method that was asked to be called
	 * @param  array  $arguments   An array of the arguments passed to the method
	 * @return mixed               Can return anything
	 *
	 * @throws \ReflectionException
	 */
	public function __call( $method_name, $arguments ) {
		$this->__method_name = $method_name;
		$this->__arguments   = $arguments;

		if ( $this->route_exists() ) {
			return $this->route_request();
		}
	}

	/**
	 * Check if the route has been defined
	 *
	 * @return bool
	 */
	private function route_exists() : bool {
		if ( property_exists( $this, 'routes' ) ) {
			return array_key_exists( $this->__method_name, $this->routes );
		}
		return false;
	}

	/**
	 * Build and do the route
	 *
	 * @throws \ReflectionException
	 */
	private function route_request() {
		$named_arguments = $this->get_named_arguments();

		list( $to_class, $to_method, $on_error ) = $this->get_route_parts();

		return $this->do_route( $to_class, $to_method, $named_arguments, $on_error );
	}

	/**
	 * Map the arguments passed into the method to the
	 * expected arguments defined by the route
	 *
	 * @return array
	 */
	private function get_named_arguments() : array {
		// name the parameters
		$named_arguments = [];

		if ( array_key_exists( 'expects', $this->routes[ $this->__method_name ] ) ) {
			$expects = $this->routes[ $this->__method_name ]['expects'];

			foreach ( $expects as $index => $parameter_name ) {
				if ( array_key_exists( $index, $this->__arguments ) ) {
					$named_arguments[ $parameter_name ] = $this->__arguments[ $index ];
				}
			}
		}

		return $named_arguments;
	}

	/**
	 * Get the individual parts of the route, including
	 * the class and method to route to
	 *
	 * @return array
	 */
	private function get_route_parts() : array {
		$to = $this->routes[ $this->__method_name ]['to'];
		if ( \is_array( $to ) ) {
			$to_parts = $to;
		} else {
			$to_parts = explode( '@', $to );
		}

		$to_class  = $to_parts[0];
		$to_method = $to_parts[1];

		$on_error = null;
		if ( array_key_exists( 'on_error', $this->routes[ $this->__method_name ] ) ) {
			$on_error = $this->routes[ $this->__method_name ]['on_error'];
		}

		return [ $to_class, $to_method, $on_error ];
	}

	/**
	 * Resolve dependencies for dependency injection
	 * and call the given route
	 *
	 * @param string   $class_name      The class to route to
	 * @param string   $method_name     The method name to call in the give class
	 * @param array    $named_arguments Associative array of suggested arguments
	 * @param callback $on_error        Callback if a validator fails
	 * @return mixed
	 * @throws \ReflectionException
	 */
	private function do_route( $class_name, $method_name, $named_arguments, $on_error ) {
		if ( \is_object( $class_name ) ) {
			return $this->call(
				array(
					$class_name,
					$method_name,
				),
				$named_arguments
			);
		}

		$injector_factory = new Dependency_Injection_Factory(
			$class_name,
			$method_name,
			$named_arguments
		);

		list(
			$obj,
			$dependencies,
			$validators
		) = $injector_factory->build();

		// Check all validators
		foreach ( $validators as $validator ) {
			$message = $validator->validate( $on_error );

			if ( $message ) {
				return $message;
			}
		}

		return $this->call(
			array(
				$obj,
				$method_name,
			),
			$dependencies
		);
	}

	/**
	 * Call the given function with arguments
	 *
	 * @param  array|string $callback  Function to call
	 * @param  array|mixed  $arguments Arguments to pass to the function
	 * @return mixed
	 */
	private function call( $callback, $arguments ) {
		return \call_user_func_array( $callback, $arguments );
	}
}
