<?php


namespace PdkPluginBoilerplate\Tests\Mocks\ContainerBuild;


class DependencyFour {


	/**
	 * @var null
	 */
	public $value;


	/**
	 * @param null $value
	 */
	public function __construct( $value = null ) {
		$this->value = $value;
	}


}