<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests;

use Joomla\Application\AbstractWebApplication;
use Joomla\OAuth2\Client;
use Joomla\Google\Auth\OAuth2;
use Joomla\Registry\Registry;
use Joomla\Http\Http;
use Joomla\Input\Input;
use PHPUnit\Framework\TestCase;

/**
 * Base test case for Google object tests.
 *
 * @since  1.0
 */
abstract class GoogleTestCase extends TestCase
{
	/**
	 * @var    Registry  Options for the Client object.
	 */
	protected $options;

	/**
	 * @var    Http  Mock client object.
	 */
	protected $http;

	/**
	 * @var    Input  The input object to use in retrieving GET/POST data.
	 */
	protected $input;

	/**
	 * @var    Client  The OAuth2 client for sending requests to Google.
	 */
	protected $oauth;

	/**
	 * @var    OAuth2  The Google OAuth client for sending requests.
	 */
	protected $auth;

	/**
	 * @var  AbstractWebApplication|\PHPUnit_Framework_MockObject_MockObject  The application object to send HTTP headers for redirects.
	 */
	protected $application;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$_SERVER['HTTP_HOST'] = 'mydomain.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$this->options = new Registry;

		$this->http = $this->getMockBuilder('Joomla\\Http\\Http')
			->setMethods(array('head', 'get', 'delete', 'trace', 'post', 'put', 'patch'))
			->setConstructorArgs(array($this->options))
			->getMock();

		$this->input = new Input;
		$this->application = $this->getMockForAbstractClass('Joomla\\Application\\AbstractWebApplication');
		$this->oauth = new Client($this->options, $this->http, $this->input, $this->application);
		$this->auth = new OAuth2($this->options, $this->oauth);

		$token['access_token'] = 'accessvalue';
		$token['refresh_token'] = 'refreshvalue';
		$token['created'] = time() - 1800;
		$token['expires_in'] = 3600;
		$this->oauth->setToken($token);
	}
}

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  object
 *
 * @since   1.0
 */
function picasaAlbumCallback($url, array $headers = null, $timeout = null)
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
 *
 * @since   1.0
 */
function picasaBadXmlCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/atom+xml');
	$response->body = '<feed />';

	return $response;
}
