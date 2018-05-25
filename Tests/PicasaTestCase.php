<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests;

/**
 * Base test case for Picasa object tests.
 */
abstract class PicasaTestCase extends GoogleTestCase
{
	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   mixed    $data     Either an associative array or a string to be sent with the request.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function dataPicasaAlbumCallback($url, $data, array $headers = null, $timeout = null)
	{
		$this->assertContains('<title>New Title</title>', $data);

		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'application/atom+xml');
		$response->body = $data;

		return $response;
	}

	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function emptyPicasaCallback($url, array $headers = null, $timeout = null)
	{
		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'application/atom+xml');
		$response->body = '';

		return $response;
	}

	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function picasaAlbumCallback($url, array $headers = null, $timeout = null)
	{
		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'text/html');
		$response->body = file_get_contents(__DIR__ . '/Stubs/album.txt');

		return $response;
	}

	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function picasaAlbumlistCallback($url, array $headers = null, $timeout = null)
	{
		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'application/atom+xml');
		$response->body = file_get_contents(__DIR__ . '/Stubs/albumlist.txt');

		return $response;
	}

	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function picasaBadXmlCallback($url, array $headers = null, $timeout = null)
	{
		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'application/atom+xml');
		$response->body = '<feed />';

		return $response;
	}

	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   mixed    $data     Either an associative array or a string to be sent with the request.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function picasaDataExceptionCallback($url, $data, array $headers = null, $timeout = null)
	{
		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'application/atom+xml');
		$response->body = 'BADDATA';

		return $response;
	}

	/**
	 * Dummy method
	 *
	 * @param   string   $url      Path to the resource.
	 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
	 * @param   integer  $timeout  Read timeout in seconds.
	 *
	 * @return  object
	 */
	public function picasaExceptionCallback($url, array $headers = null, $timeout = null)
	{
		$response = new \stdClass;

		$response->code = 200;
		$response->headers = array('Content-Type' => 'application/atom+xml');
		$response->body = 'BADDATA';

		return $response;
	}
}
