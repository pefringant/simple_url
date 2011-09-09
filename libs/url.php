<?php
/**
 * CakePHP Lib to generate url, quicker than passing an array
 * 
 * Warning : requires PHP 5.3+
 * 
 * @author Pierre-Emmanuel Fringant (pierre.fringant@gmail.com)
 * @link http://www.formation-cakephp.com
 */
class Url {
	/**
	 * Catch all.
	 * 
	 * @param string $name Name of the called method. Will be parsed to find the plugin, controller, action and prefix.
	 * @param array $args Arguments will be passed to the Router::url() method
	 */
	public static function __callStatic($name, $args) {
		$plugin = $controller = $prefix = $action = null;
		
		$camelCasedName = Inflector::camelize($name);
		
		// 1. Plugin ?
		$plugins = App::objects('plugin');
		usort($plugins, array('Url', 'sortByLengthDesc'));

		foreach ($plugins as $p) {
			if (strpos($camelCasedName, $p) === 0) {
				$camelCasedName = substr($camelCasedName, strlen($p));
				$plugin = Inflector::underscore($p);
				break;
			}
		}
		
		// 2. Controller
		if ($plugin) {
			$path = App::pluginPath($plugin);
			$cache_key = $plugin;
		} else {
			$path = APP;
			$cache_key = 'app';
		}

		$path .= 'controllers';
		$cache_key = 'simple_url_'.$cache_key.'_controllers';
		
		if (!$controllers = Cache::read($cache_key)) {
			$controllers = App::objects('controller', $path, false);
			usort($controllers, array('Url', 'sortByLengthDesc'));
			Cache::write($cache_key, $controllers);
		}

		foreach ($controllers as $c) {
			if (strpos($camelCasedName, $c) === 0) {
				$camelCasedName = substr($camelCasedName, strlen($c));
				$controller = Inflector::underscore($c);
				break;
			}
		}
		
		$underscored_name = Inflector::underscore($camelCasedName);
		
		// 3. Prefix ?
		$prefixes = Configure::read('Routing.prefixes');
		usort($prefixes, array('Url', 'sortByLengthDesc'));
		
		foreach ($prefixes as $p) {
			if (strpos($underscored_name, $p) === 0) {
				$underscored_name = substr($underscored_name, strlen($p)+1);
				$prefix = $p;
				break;
			}
		}
		
		// 4. Action
		$action = !empty($underscored_name) ? $underscored_name : 'index';
		
		// Route parameters
		$params = compact('plugin', 'controller', 'action');
		
		foreach ($prefixes as $p) {
			$params[$p] = ($p == $prefix);
		}
		
		// Args
		if (!empty($args)) {
			foreach ($args as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $k => $v) {
						$params[$k] = $v;
					}
				} else {
					$params[] = $arg;
				}
			}
		}

		return Router::url($params);
	}
	
	/**
	 * Sorts an array by the descending length of it's values
	 * 
	 * @param string $a First string
	 * @param string $b Second string
	 */
	public static function sortByLengthDesc($a, $b){
	    return strlen($b) - strlen($a);
	}
}