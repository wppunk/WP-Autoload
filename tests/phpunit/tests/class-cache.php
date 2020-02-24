<?php
/**
 * Test cache
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

use WP_Autoload\Cache;
use WP_Mock\Tools\TestCase;

require_once __DIR__ . '/../../../classes/class-cache.php';

/**
 * Class Test_Cache
 */
class Test_Cache extends TestCase {

	/**
	 * Test __construct
	 */
	public function test___construct() {
		WP_Mock::userFunction( 'plugin_dir_path', [ 'times' => 1 ] );

		new Cache();

		$this->assertTrue( true );
	}

	/**
	 * Cache not found.
	 */
	public function test_not_exists_cache() {
		$cache = new Cache();

		$this->assertSame( '', $cache->get( '\Prefix\Autoload_Success_1' ) );
	}

	/**
	 * Get valid cache.
	 */
	public function test_update_valid_cache() {
		$class = '\Prefix\Autoload_Success_1';
		$path  = __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1.php';

		$cache = new Cache();
		$cache->update( $class, $path );

		$this->assertSame( $path, $cache->get( $class ) );
	}

	/**
	 * Have invalid cache.
	 */
	public function test_update_invalid_cache() {
		$class = '\Prefix\Autoload_Success_1';
		$path  = __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1111.php';

		$cache = new Cache();
		$cache->update( $class, $path );

		$this->assertSame( '', $cache->get( $class ) );
	}

	/**
	 * Save cache
	 */
	public function test_save() {
		global $wp_filesystem;
		$wp_filesystem = Mockery::mock( 'overload:WP_Filesystem_Direct' );
		$wp_filesystem->shouldReceive( 'put_contents' )->once();

		$cache = new Cache();
		$cache->update( '\Prefix\Autoload_Success_1', __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1.php' );
		$cache->save();

		$this->assertTrue( true );
	}

	/**
	 * Dont save cache
	 */
	public function test_dont_save() {
		$cache = new Cache();
		$cache->save();

		$this->assertTrue( true );
	}

}