<?php


namespace PdkPluginBoilerplate\Framework\Traits;


use ArrayAccess;


trait DotNotatedArraySupport {


	/**
	 * Sets the value within an array. Supports dot-notated keys
	 *
	 * @param array $array
	 * @param string $key
	 * @param $value
	 *
	 * @return mixed
	 */
	private function set( &$array, $key, $value ) {
		if ( is_null( $key ) ) {
			return $array = $value;
		}

		$keys = explode( '.', $key );

		while ( count( $keys ) > 1 ) {
			$key = array_shift( $keys );

			if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
				$array[ $key ] = [];
			}

			$array = &$array[ $key ];
		}

		$array[ array_shift( $keys ) ] = $value;

		return $array;
	}


	/**
	 * Resolves the value of a multi-dimensional array using dot notation.
	 *
	 * e.g; static::get(['a' => ['b' => 1]], 'a.b') => 1
	 *
	 * @param array $array
	 * @param string $key Dot-notated path to nested array value. Can also just be a non-nested key.
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	private function get( $array, $key, $default = null ) {
		$current = $array;
		$p       = strtok( $key, '.' );

		while ( $p !== false ) {
			if ( ! isset( $current[ $p ] ) ) {
				return $default;
			}
			$current = $current[ $p ];
			$p       = strtok( '.' );
		}

		return $current;
	}


	/**
	 * @param array $array The array to check
	 * @param string $key Dot-notated path the nested array value. Can also just be a non-nested key.
	 *
	 * @return bool
	 */
	private function has( $array, $key ) {
		$keys = explode( '.', $key );

		$current_array = $array;

		while ( count( $keys ) > 1 ) {
			$current_key = array_shift( $keys );

			$key_exists = isset( $current_array[ $current_key ] );

			$value_is_array = (
				is_array( $current_array[ $current_key ] )
				|| $current_array[ $current_key ] instanceof ArrayAccess
			);

			if ( $key_exists and $value_is_array ) {
				$current_array = $current_array[ $current_key ];
			} else {
				return false;
			}
		}

		return isset( $current_array[ array_shift( $keys ) ] );
	}


}