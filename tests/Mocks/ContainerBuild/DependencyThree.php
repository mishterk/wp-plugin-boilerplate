<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class DependencyThree {


	/**
	 * @var DependencyFour
	 */
	private $four;


	/**
	 * @var string
	 */
	private $value;


	/**
	 * @var bool
	 */
	private $value2;


	/**
	 * @param DependencyFour $four
	 * @param string $value
	 * @param bool $value2
	 */
	public function __construct( DependencyFour $four, $value = 'string', $value2 = false ) {
		$this->four   = $four;
		$this->value  = $value;
		$this->value2 = $value2;
	}


}