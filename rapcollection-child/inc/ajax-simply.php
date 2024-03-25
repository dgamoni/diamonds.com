<?php

/**
 * Plugin Name: AJAX Simply
 * Description: Allows to create AJAX applications on WordPress by simple way.
 *
 * Author URI: http://wp-kama.ru/
 * Author: Kama
 * Plugin URI:
 *
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Require PHP: 5.3
 *
 * Version: 1.1.9
 */


define( 'AJAXS_PATH', wp_normalize_path( __DIR__ ) .'/' );

# for plugin and must-use plugin
//define( 'AJAXS_URL',  plugin_dir_url(__FILE__) );
# for theme
 define( 'AJAXS_URL', strtr( AJAXS_PATH, array( wp_normalize_path(get_template_directory()) => get_template_directory_uri() ) ) );

## allow ajaxs basic nonce check
//add_filter( 'allow_ajaxs_nonce', '__return_true' );

## include ajaxs js in HTML (inline not as file)
add_filter( 'ajaxs_use_inline_js', '__return_true' );

// init plugin
add_action( (is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts'), 'ajax_simply_enqueue_scripts', 9999 );

function ajax_simply_enqueue_scripts(){

	$js           = 'ajaxs.min.js'; // ajaxs.js
	$extra_object = 'jxs'; // can't be 'ajaxs'

	// 'ajaxs_front_request_url' hook allow to change AJAX request URL for front
	$request_url = admin_url( 'admin-ajax.php', 'relative' );
	$url   = is_admin() ? $request_url : apply_filters('ajaxs_front_request_url', $request_url );
	$nonce = wp_create_nonce('ajaxs_action');
	$extra_data = array(
		'url' => "$url?action=ajaxs_action&ajaxs_nonce=$nonce&jxs_act="
	);

	// inline script
	if( apply_filters( 'ajaxs_use_inline_js', false ) ){
		if( ! wp_script_is( 'jquery', 'enqueued' ) )
			wp_enqueue_script( 'jquery' );

		$handler = wp_script_is( 'jquery-core', 'enqueued' ) ? 'jquery-core' : 'jquery';

		$script = "var $extra_object = " . wp_json_encode( $extra_data ) . ';';
		$script .= file_get_contents( AJAXS_PATH . $js );

		wp_add_inline_script( $handler, $script );
	}
	// enqueue script
	else {
		$data = get_file_data( __FILE__, array('ver'=>'Version') );
		$ver = WP_DEBUG ? filemtime( AJAXS_PATH . $js ) : $data['ver'];

		wp_enqueue_script( 'ajaxs_script', AJAXS_URL . $js, array('jquery'), $ver, true );
		wp_localize_script( 'ajaxs_script', $extra_object, $extra_data );
	}

}

// DOING_AJAX - DOING AJAXS - INIT all in earle state
if( isset($_REQUEST['jxs_act']) ){

	// @ ini_set( 'display_errors', 1 ); // no need - works on any state of 'display_errors' - 0 or 1

	// when handler function echo or die() string data, but not return it. Or when php errors occur.
	// or for functions like: 'wp_send_json_error()' which echo and die()
	ob_start( function($buffer){
		// check return of handler function
		if( AJAX_Simply_Core::$__buffer === null )
			AJAX_Simply_Core::$__buffer = $buffer;

		return ''; // clear original buffer: die, exit or php errors. We dont need it, as we save it...
	} );

	// catch not fatal errors in early state...
	if( WP_DEBUG && WP_DEBUG_DISPLAY ){
		set_error_handler( array('AJAX_Simply_Core', '_console_error_massage') );
	}

	// for cases when handler function uses: die, exit. And
	// catch fatal errors in early state...
	register_shutdown_function( array('AJAX_Simply_Core', '_shutdown_function') );

	// need it in early state for catching errors response...
	if( ! headers_sent() ){
		@ header( 'Content-Type: application/json; charset=' . get_option('blog_charset') );
	}

	add_action( 'wp_ajax_'.'ajaxs_action',        array( 'AJAX_Simply_Core', 'init'), 0 );
	add_action( 'wp_ajax_nopriv_'.'ajaxs_action', array( 'AJAX_Simply_Core', 'init'), 0 );

}

## helper function for get current $jx object somewhere else in ajaxs functions
function jx(){
	return AJAX_Simply_Core::$instance;
}

class AJAX_Simply_Core {

	public $data     = array(); // POST data

	static $__reply  = array();

	static $__buffer = null;

	static $instance = null;

	function __construct(){}

	// for isset() and empty()
	function __isset( $name ){
		return $this->_get_param( $name ) !== null;
	}

	function __get( $name ){
		return $this->_get_param( $name );
	}

	function _get_param( $name ){

		if( !empty($_FILES) ){
			foreach( $_FILES as & $files ) $files = self::_maybe_compact_files( $files );
		}

		if( isset($_FILES[ $name ]) ) return $_FILES[ $name ];

		if( isset($this->data[ $name ]) ) return $this->data[ $name ];

		// в конце
		if( $name === 'files' ) return $_FILES;

		return null;
	}

	## соберет неудобный массив файлов п компактные массивы каждого файла и добавит полученный массив в индекс 'compact'...
	static function _maybe_compact_files( $files ){
		if( isset($files['compact']) )                               return $files; // уже добавлен
		if( !isset($files['name']) || ! is_array( $files['name'] ) ) return $files; // если name не массив, то поле не multiple...

		foreach( $files as $key => $data ){
			foreach( $data as $index => $val ) $files['compact'][ $index ][ $key ] = $val; // добалвяем
		}

		return $files;
	}

	static function init(){

		$_DATA = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

		// not ajaxs action
		if( empty($_REQUEST['jxs_act']) )
			return;

		// ajaxs_nonce - not depends on POST or GET request method
		$_DATA['ajaxs_nonce'] = isset($_REQUEST['ajaxs_nonce']) ? $_REQUEST['ajaxs_nonce'] : '';

		// action can be:               function_name | class::method
		// it will turns to:      ajaxs_function_name | AJAXS_class::method
		// or to:            ajaxs_priv_function_name | AJAXS_PRIV_class::method
		// or to:                                     | class::ajaxs_method
		// or to:                                     | class::ajaxs_priv_method
		$jxs_act = $_REQUEST['jxs_act'];
		$jxs_act = preg_replace( '~[^a-zA-Z0-9_:\->()]~', '', $jxs_act ); // delete unwonted characters
		$jxs_act = preg_replace( '~\(\)$~', '', $jxs_act ); // delete '()' at the end

		unset( $_DATA['action'], $_DATA['jxs_act'] ); // clear no need internal vars

		// init instance -------------------------------------

		$jx = self::$instance = new self;

		$jx->data = wp_unslash( $_DATA );

		// basic nonce check
		if( apply_filters('allow_ajaxs_nonce', false) && ! wp_verify_nonce( $jx->ajaxs_nonce, 'ajaxs_action' ) ){
			wp_die( -1, 403 );
		}

		$action = $jxs_act;

		// для вставки общих проверок через фильтр, перед обработкой запроса.
		// Например, если для группы запросов нужна одна и та же проверка прав доступа,
		// чтобы не писать каждый раз в фукнции обработчике одно и тоже, можно через этот хук добавить проверку один раз.
		$allow = apply_filters( 'ajaxs_allow_process', true, $action, $jx );
		if( ! $allow && $allow !== null ){
			wp_die( -1, 403 );
		}

		// parse class::method action
		if(     strpos($action, '->') ) $action = explode('->', $action ); // 'myclass->method'
		elseif( strpos($action, '::') ) $action = explode('::', $action ); // 'myclass::method'

		$actions = array();
		$fn__has_prefix = function( $string ){
			return preg_match( '/^ajaxs_/i', $string );
		};

		// class method
		if( is_array($action) ){
			list( $class, $method ) = $action;

			// добавим превиксы, если в названии класса и метода нет префикса: 'AJAXS_' или 'AJAXS_PRIV_' (для класса) и 'ajaxs_' или 'ajaxs_priv_' (для метода)
			if( $fn__has_prefix($class) || $fn__has_prefix($method) ){
				$actions[] = array( $class, $method );
			}
			else {
				$actions[] = array( "AJAXS_{$class}", $method );
				$actions[] = array( $class, "ajaxs_$method" );

				if( is_user_logged_in() ){
					$actions[] = array( "AJAXS_PRIV_$class", $method );
					$actions[] = array( $class, "ajaxs_priv_$method" );
				}
			}


		}
		// function
		else {
			$action = preg_replace( '~[^A-Za-z0-9_]~', '', $action );

			if( $fn__has_prefix($action) ){
				$actions[] = $action;
			}
			else {
				$actions[] = "ajaxs_{$action}";

				if( is_user_logged_in() )
					$actions[] = "ajaxs_priv_{$action}";
			}

		}

		// CALL action

		// пробуем найти обработчик
		foreach( $actions as $_action ){
			if( is_callable($_action) ){
				$action_found = true;
				self::$__buffer = call_user_func( $_action, $jx );
			}
		}

		// нет подходящей фукнции - юзаем базовые хуки WP AJAX: 'wp_ajax_{$action}' или 'wp_ajax_nopriv_{$action}'
		if( empty($action_found) ){

			if ( is_user_logged_in() )
				$hook_name = "wp_ajax_{$jxs_act}";
			else
				$hook_name = "wp_ajax_nopriv_{$jxs_act}";

			$return = apply_filters( $hook_name, $jx );

			if( $return instanceof AJAX_Simply_Core )
				$jx->console( 'AJAXS ERROR: There is no function, no method, no hook for handle AJAXS request in PHP! Current action: "'. $jxs_act .'"', 'error' );
			else
				self::$__buffer = $return;
		}

		//ob_end_clean(); // работает при exit;

		exit;
	}

	static function _shutdown_function(){

		if( WP_DEBUG && WP_DEBUG_DISPLAY ){
			AJAX_Simply_Core::_console_error_massage( error_get_last() ); // for fatal error
		}

		$reply  = self::$__reply;
		$buffer = self::$__buffer;

		// if handler function return data
		if( ! isset($reply['response']) ){
			$reply['response'] = null;

			if( $buffer !== null ){
				// $is_json - for functions like 'wp_send_json_error()'
				$is_json = is_string( $buffer ) && is_array( json_decode($buffer, true) ) && ( json_last_error() == JSON_ERROR_NONE );

				$reply['response'] = $is_json ? json_decode( $buffer ) : $buffer;
			}

		}

		// уберем лишний элемент response если нет extra и отдадим как есть
		if( empty($reply['extra']) ){
			$reply = $reply['response'];
		}

		echo wp_json_encode( $reply );
	}

	static function _console_error_massage( $args ){
		// error_get_last() has no error
		if( $args === null ) return;

		// error_get_last()
		if( is_array($args) ){
			list( $errno, $errstr, $errfile, $errline ) = array_values( $args );

			// only for fatal errors, because we cant define @suppress here
			if( ! in_array( $errno, array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING) ) )
				 return;

			$console_type = 'error'; // fatal error
		}
		// set_error_handler()
		else {
			list( $errno, $errstr, $errfile, $errline ) = func_get_args();
		}

		// for @suppress
		$errno = $errno & error_reporting();
		if( $errno == 0 ) return;

		if( ! defined('E_STRICT') )            define('E_STRICT', 2048);
		if( ! defined('E_RECOVERABLE_ERROR') ) define('E_RECOVERABLE_ERROR', 4096);

		$err_names = array(
			// fatal errors
			E_ERROR             => 'Fatal error',
			E_PARSE             => 'Parse Error',
			E_CORE_ERROR        => 'Core Error',
			E_CORE_WARNING      => 'Core Warning',
			E_COMPILE_ERROR     => 'Compile Error',
			E_COMPILE_WARNING   => 'Compile Warning',
			// other errors
			E_WARNING           => 'Warning',
			E_NOTICE            => 'Notice',
			E_STRICT            => 'Strict Notice',
			E_RECOVERABLE_ERROR => 'Recoverable Error',
			// user type errors
			E_USER_ERROR        => 'User Error',
			E_USER_WARNING      => 'User Warning',
			E_USER_NOTICE       => 'User Notice',
		);

		$err_name = "Unknown error ($errno)";
		if( isset($err_names[ $errno ]) )
			$err_name = $err_names[ $errno ];

		static $once;
		if( ! $once ){
			$once = 1;
			AJAX_Simply_Core::_static_console( 'PHP errors:' ); // title for errors
		}

		if( empty($console_type) ){
			$console_type = 'log';
		}
		elseif( in_array( $errno, array(E_WARNING, E_USER_WARNING) ) ){
			$console_type = 'warn';
		}

		AJAX_Simply_Core::_static_console( "PHP $err_name: $errstr in $errfile on line $errline\n", $console_type );

		return true; // Don't execute PHP internal error handler for set_error_handler()
	}


	// RESPONSE METHODS -------------------------

	// alias of success()
	function done( $data = null ){
		$this->success( $data );
	}
	// alias of success()
	function ok( $data = null ){
		$this->success( $data );
	}

	function success( $data = null ){
		self::$__reply['response'] = array(
			'success' => true,
			'ok'      => true, // alias of success
			'error'   => false,
			'data'    => $data
		);

		exit;
	}

	function error( $data = null ){
		self::$__reply['response'] = array(
			'success' => false,
			'ok'      => false, // alias of success
			'error'   => true,
			'data'    => $data
		);

		exit;
	}


	## $delay в милисекундах: 1000 = 1 секунда
	function reload( $delay = 0 ){
		self::$__reply['extra']['reload'] = $delay ? intval($delay) : 1;
	}

	## $delay в милисекундах: 1000 = 1 секунда
	function redirect( $url, $delay = 0 ){
		self::$__reply['extra']['redirect'] = array( wp_sanitize_redirect($url), $delay );
	}

	function html( $selector, $html ){
		self::$__reply['extra']['html'][] = array( $selector, $html );
	}

	## алиас для console
	function log( $data, $type = 'log' ){
		$this->console( $data, $type );
	}

	## $type: log, warn, error. Except multiple parameters
	function console( $data, $type = 'log' ){
		$args = func_get_args();

		// if last element is: log, warn, error
		if( in_array( end($args), array('log','warn','error') ) ){
			$type = array_pop( $args ); // cut last element

			foreach( $args as $data )
				self::_static_console( $data, $type );
		}
		else {
			foreach( $args as $data )
				self::_static_console( $data, 'log' );
		}

	}

	## var_dump to console. Except multiple parameters
	function dump( $data ){
		foreach( func_get_args() as $data ){
			ob_start();
			var_dump( $data );
			$data = ob_get_clean();

			self::_static_console( $data );
		}
	}

	function alert( $data ){
		if( is_array($data) || is_object($data) ){
			$data = print_r( $data, 1 );
		}

		self::$__reply['extra']['alert'][] = $data;
	}

	function trigger( $event, $selector = false, $args = array() ){
		self::$__reply['extra']['trigger'][] = array( $event, $selector, $args );
	}

	function call( $func_name /* $param1, $param2 */ ){
		$args = array_slice( func_get_args(), 1 );

		self::$__reply['extra']['call'][] = array( $func_name, $args );
	}

	function jseval( $jscode  ){
		self::$__reply['extra']['jseval'][] = $jscode;
	}

	// normaly not used: PHP function can just return any value
	function response( $val ){
		self::$__reply['response'] = $val;
	}


	## internal do not use - uses internally for PHP errors
	static function _static_console( $data, $type = 'log' ){
		if( is_array($data) || is_object($data) ){
			$data = print_r( $data, 1 );
		}

		self::$__reply['extra']['console'][] = array( $data, $type );
	}

}
