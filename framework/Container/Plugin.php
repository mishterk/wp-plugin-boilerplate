<?php


namespace PdkPluginBoilerplate\Framework\Container;


class Plugin extends Application {


	protected $base_dir;
	protected $base_url;


	/**
	 * @param $base_dir
	 * @param $base_url
	 */
	public function __construct( $base_dir, $base_url ) {
		$this->base_dir = $base_dir;
		$this->base_url = $base_url;

		parent::__construct( $base_dir );
	}


}