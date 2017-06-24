<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests\Data\Plus;

use Joomla\Google\Data\Plus\Activities;
use Joomla\Google\Tests\GoogleTestCase;

/**
 * Test class for Joomla\Google\Data\Plus\Activities
 */
class ActivitiesTest extends GoogleTestCase
{
	/**
	 * Object under test.
	 *
	 * @var  DataPlusActivities
	 */
	protected $object;

	/**
	 * Sample JSON string.
	 *
	 * @var  string
	 */
	protected $sampleString = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	/**
	 * Sample JSON error message.
	 *
	 * @var  string
	 */
	protected $errorString = '{"error": {"message": "Generic Error."}}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Activities($this->options, $this->auth);

		$this->object->setOption('clientid', '01234567891011.apps.googleusercontent.com');
		$this->object->setOption('clientsecret', 'jeDs8rKw_jDJW8MMf-ff8ejs');
		$this->object->setOption('redirecturi', 'http://localhost/oauth');
	}

	/**
	 * Tests the auth method
	 */
	public function testAuth()
	{
		$this->assertEquals($this->auth->authenticate(), $this->object->authenticate());
	}

	/**
	 * Tests the isauth method
	 */
	public function testIsAuth()
	{
		$this->assertEquals($this->auth->isAuthenticated(), $this->object->isAuthenticated());
	}

	/**
	 * Tests the listComments method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testListActivities()
	{
		$userId = 'me';
		$collection = 'public';
		$fields = 'title,kind,url';
		$max = 5;
		$token = 'EAoaAA';
		$alt = 'json';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'people/' . $userId . '/activities/' . $collection . '?fields=' . $fields . '&maxResults=' . $max .
			'&pageToken=' . $token . '&alt=' . $alt;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->listActivities($userId, $collection, $fields, $max, $token, $alt),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->listActivities($userId, $collection),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the getActivity method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetActivity()
	{
		$id = 'z12ezrmamsvydrgsy221ypew2qrkt1ja404';
		$fields = 'title,kind,url';
		$alt = 'json';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'activities/' . $id . '?fields=' . $fields . '&alt=' . $alt;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getActivity($id, $fields, $alt),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->getActivity($id, $fields),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the search method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSearch()
	{
		$query = 'test search';
		$fields = 'aboutMe,birthday';
		$language = 'en-GB';
		$max = 5;
		$order = 'best';
		$token = 'EAoaAA';

		$returnData = new \stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$url = 'activities?query=' . urlencode($query) . '&fields=' . $fields . '&language=' . $language .
			'&maxResults=' . $max . '&orderBy=' . $order . '&pageToken=' . $token;

		$this->http->expects($this->once())
		->method('get')
		->with($url)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->search($query, $fields, $language, $max, $order, $token),
			$this->equalTo(json_decode($this->sampleString, true))
		);

		// Test return false.
		$this->oauth->setToken(null);
		$this->assertThat(
			$this->object->search($query),
			$this->equalTo(false)
		);
	}
}
