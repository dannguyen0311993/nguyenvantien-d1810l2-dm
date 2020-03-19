<?php
/**
 * Theme storage manipulations
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('ostende_storage_get')) {
	function ostende_storage_get($var_name, $default='') {
		global $OSTENDE_STORAGE;
		return isset($OSTENDE_STORAGE[$var_name]) ? $OSTENDE_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('ostende_storage_set')) {
	function ostende_storage_set($var_name, $value) {
		global $OSTENDE_STORAGE;
		$OSTENDE_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('ostende_storage_empty')) {
	function ostende_storage_empty($var_name, $key='', $key2='') {
		global $OSTENDE_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($OSTENDE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($OSTENDE_STORAGE[$var_name][$key]);
		else
			return empty($OSTENDE_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('ostende_storage_isset')) {
	function ostende_storage_isset($var_name, $key='', $key2='') {
		global $OSTENDE_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($OSTENDE_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($OSTENDE_STORAGE[$var_name][$key]);
		else
			return isset($OSTENDE_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('ostende_storage_inc')) {
	function ostende_storage_inc($var_name, $value=1) {
		global $OSTENDE_STORAGE;
		if (empty($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = 0;
		$OSTENDE_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('ostende_storage_concat')) {
	function ostende_storage_concat($var_name, $value) {
		global $OSTENDE_STORAGE;
		if (empty($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = '';
		$OSTENDE_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('ostende_storage_get_array')) {
	function ostende_storage_get_array($var_name, $key, $key2='', $default='') {
		global $OSTENDE_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($OSTENDE_STORAGE[$var_name][$key]) ? $OSTENDE_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($OSTENDE_STORAGE[$var_name][$key][$key2]) ? $OSTENDE_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('ostende_storage_set_array')) {
	function ostende_storage_set_array($var_name, $key, $value) {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if ($key==='')
			$OSTENDE_STORAGE[$var_name][] = $value;
		else
			$OSTENDE_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('ostende_storage_set_array2')) {
	function ostende_storage_set_array2($var_name, $key, $key2, $value) {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if (!isset($OSTENDE_STORAGE[$var_name][$key])) $OSTENDE_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$OSTENDE_STORAGE[$var_name][$key][] = $value;
		else
			$OSTENDE_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('ostende_storage_merge_array')) {
	function ostende_storage_merge_array($var_name, $key, $value) {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if ($key==='')
			$OSTENDE_STORAGE[$var_name] = array_merge($OSTENDE_STORAGE[$var_name], $value);
		else
			$OSTENDE_STORAGE[$var_name][$key] = array_merge($OSTENDE_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('ostende_storage_set_array_after')) {
	function ostende_storage_set_array_after($var_name, $after, $key, $value='') {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if (is_array($key))
			ostende_array_insert_after($OSTENDE_STORAGE[$var_name], $after, $key);
		else
			ostende_array_insert_after($OSTENDE_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('ostende_storage_set_array_before')) {
	function ostende_storage_set_array_before($var_name, $before, $key, $value='') {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if (is_array($key))
			ostende_array_insert_before($OSTENDE_STORAGE[$var_name], $before, $key);
		else
			ostende_array_insert_before($OSTENDE_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('ostende_storage_push_array')) {
	function ostende_storage_push_array($var_name, $key, $value) {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($OSTENDE_STORAGE[$var_name], $value);
		else {
			if (!isset($OSTENDE_STORAGE[$var_name][$key])) $OSTENDE_STORAGE[$var_name][$key] = array();
			array_push($OSTENDE_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('ostende_storage_pop_array')) {
	function ostende_storage_pop_array($var_name, $key='', $defa='') {
		global $OSTENDE_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($OSTENDE_STORAGE[$var_name]) && is_array($OSTENDE_STORAGE[$var_name]) && count($OSTENDE_STORAGE[$var_name]) > 0) 
				$rez = array_pop($OSTENDE_STORAGE[$var_name]);
		} else {
			if (isset($OSTENDE_STORAGE[$var_name][$key]) && is_array($OSTENDE_STORAGE[$var_name][$key]) && count($OSTENDE_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($OSTENDE_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('ostende_storage_inc_array')) {
	function ostende_storage_inc_array($var_name, $key, $value=1) {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if (empty($OSTENDE_STORAGE[$var_name][$key])) $OSTENDE_STORAGE[$var_name][$key] = 0;
		$OSTENDE_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('ostende_storage_concat_array')) {
	function ostende_storage_concat_array($var_name, $key, $value) {
		global $OSTENDE_STORAGE;
		if (!isset($OSTENDE_STORAGE[$var_name])) $OSTENDE_STORAGE[$var_name] = array();
		if (empty($OSTENDE_STORAGE[$var_name][$key])) $OSTENDE_STORAGE[$var_name][$key] = '';
		$OSTENDE_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('ostende_storage_call_obj_method')) {
	function ostende_storage_call_obj_method($var_name, $method, $param=null) {
		global $OSTENDE_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($OSTENDE_STORAGE[$var_name]) ? $OSTENDE_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($OSTENDE_STORAGE[$var_name]) ? $OSTENDE_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('ostende_storage_get_obj_property')) {
	function ostende_storage_get_obj_property($var_name, $prop, $default='') {
		global $OSTENDE_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($OSTENDE_STORAGE[$var_name]->$prop) ? $OSTENDE_STORAGE[$var_name]->$prop : $default;
	}
}
?>