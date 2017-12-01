<?php

namespace Nectary\Factories\Implementations;

use Nectary\Factories\Factory;
use Nectary\Singletons\Singleton;
use Nectary\Requests\Request;

/**
 * Given a class name and method for the class
 * (the constructor is an allowed method), this factory
 * will automatically build the given object and
 * provide the dependencies for the given method using
 * the given arguments as suggestions.
 *
 * @extends Factory
 */
class Dependency_Injection_Factory extends Factory {
	private $class_name;
	private $method_name;
	private $arguments;
	private $validators;

	/**
	 * Setup object variables
	 *
	 * @constructor
	 * @param $class_name string
	 * @param $method_name string
	 * @param $arguments array
	 */
	public function __construct( $class_name, $method_name, $arguments ) {
		$this->class_name  = $class_name;
		$this->method_name = $method_name;
		$this->arguments   = $arguments;
		$this->validators  = [];
	}

	/**
	 * Build the object, its dependencies, and list
	 * any validators that should be checked
	 *
	 * @override
	 * @throws \ReflectionException
	 */
	public function build() {
		$reflector = new \ReflectionMethod(
			$this->class_name,
			$this->method_name
		);

		$dependencies = $this->get_dependencies(
			$reflector,
			$this->arguments
		);

		$obj = $this->make(
			$this->class_name,
			$this->arguments
		);

		return [ $obj, $dependencies, $this->validators ];
	}

	/**
	 * Get the dependencies for a given reflection object
	 *
	 * @param $reflector \ReflectionMethod Reflection object to gather dependencies from
	 * @param $named_arguments array An associative array of suggested arguments
	 * @return array
	 * @throws \ReflectionException
	 */
	private function get_dependencies( \ReflectionMethod $reflector, $named_arguments ) {
		$reflector_parameters = $reflector->getParameters();

		$dependencies = $this->resolve_dependencies( $reflector_parameters, $named_arguments );

		return $dependencies;
	}

	/**
	 * Resolve all dependencies recursively. Untyped parameters will
	 * have the $named_arguments injected into them
	 *
	 * @param $reflector_parameters array<ReflectionParameters> Will check these for dependencies
	 * @param $named_arguments array An associative array of suggested arguments
	 * @return array
	 * @throws \ReflectionException
	 */
	private function resolve_dependencies( $reflector_parameters, $named_arguments = [] ) {
		// map the named_arguments to the reflector_parameters
		$dependencies = [];

		foreach ( $reflector_parameters as $parameters ) {
			if ( array_key_exists( $parameters->getName(), $named_arguments ) ) {
				$dependencies[] = $named_arguments[ $parameters->getName() ];

				// TODO Need to determine whether used parameters should
				// be popped. If they should, uncomment out the following
				// line.
				// unset( $named_arguments[ $parameters->getName() ] );
			} elseif ( $parameters->getClass() ) {
				$dependencies[] = $this->make( $parameters->getClass()->name, $named_arguments );
			} elseif ( $parameters->isDefaultValueAvailable() ) {
				$dependencies[] = $parameters->getDefaultValue();
			} else {
				$dependencies[] = $named_arguments;
			}
			// TODO inject default values (if available)
		}
		return $dependencies;
	}

	/**
	 * Given a class and arguments to inject into that class, this
	 * will create an instance of the given object.
	 *
	 * This will handle special cases as well.
	 *
	 * @param $class_name string The name of the class to make
	 * @param $named_arguments array The arguments to inject into the class
	 * @return Mixed An instance of the class
	 * @throws \ReflectionException
	 */
	private function make( $class_name, $named_arguments ) {
		$reflector   = new \ReflectionClass( $class_name );
		$constructor = $reflector->getConstructor();

		if ( is_subclass_of( $class_name, Singleton::class ) ) {
			return $class_name::get_instance();
		}
		if ( $reflector->isAbstract() ) {
			return null;
		}
		if ( null === $constructor ) {
			return new $class_name();
		}

		$dependencies = $this->get_dependencies( $constructor, $named_arguments );
		$obj          = $reflector->newInstanceArgs( $dependencies );

		// Handle special case of registering requests
		if ( $obj instanceof Request ) {
			$this->validators[] = $obj;
		}

		return $obj;
	}
}
