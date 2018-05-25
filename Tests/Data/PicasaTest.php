<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests\Data;

use Joomla\Google\Data\Picasa;
use Joomla\Google\Tests\PicasaTestCase;

/**
 * Test class for \Joomla\Google\Data\Picasa
 */
class JGoogleDataPicasaTest extends PicasaTestCase
{
	/**
	 * Object under test.
	 *
	 * @var  Picasa
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Picasa($this->options, $this->auth);

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
	 * Tests the listAlbums method
	 */
	public function testListAlbums()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback(array($this, 'picasaAlbumlistCallback')));
		$results = $this->object->listAlbums('userID');

		$this->assertEquals(count($results), 2);
		$i = 1;

		foreach ($results as $result)
		{
			$this->assertEquals(get_class($result), 'Joomla\\Google\\Data\\Picasa\\Album');
			$this->assertEquals($result->getTitle(), 'Album ' . $i);
			$i++;
		}
	}

	/**
	 * Tests the listAlbums method with wrong XML
	 *
	 * @group	JGoogle
	 * @expectedException UnexpectedValueException
	 * @return void
	 */
	public function testListAlbumsException()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback(array($this, 'picasaBadXmlCallback')));
		$this->object->listAlbums();
	}

	/**
	 * Tests the createAlbum method
	 */
	public function testCreateAlbum()
	{
		$this->http->expects($this->once())->method('post')->will($this->returnCallback(array($this, 'dataPicasaAlbumCallback')));
		$result = $this->object->createAlbum('userID', 'New Title', 'private');
		$this->assertEquals(get_class($result), 'Joomla\\Google\\Data\\Picasa\\Album');
		$this->assertEquals($result->getTitle(), 'New Title');
	}

	/**
	 * Tests the getAlbum method
	 */
	public function testGetAlbum()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback(array($this, 'picasaAlbumCallback')));
		$result = $this->object->getAlbum('https://picasaweb.google.com/data/entry/api/user/12345678901234567890/albumid/0123456789012345678');
		$this->assertEquals(get_class($result), 'Joomla\\Google\\Data\\Picasa\\Album');
		$this->assertEquals($result->getTitle(), 'Album 2');
	}

	/**
	 * Tests the setOption method
	 */
	public function testSetOption()
	{
		$this->object->setOption('key', 'value');

		$this->assertThat(
			$this->options->get('key'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the getOption method
	 */
	public function testGetOption()
	{
		$this->options->set('key', 'value');

		$this->assertThat(
			$this->object->getOption('key'),
			$this->equalTo('value')
		);
	}

	/**
	 * Tests that all functions properly return false
	 */
	public function testFalse()
	{
		$this->oauth->setToken(false);

		$functions['listAlbums'] = array('userID');
		$functions['createAlbum'] = array('userID', 'New Title', 'private');
		$functions['getAlbum'] = array('https://picasaweb.google.com/data/entry/api/user/12345678901234567890/albumid/0123456789012345678');

		foreach ($functions as $function => $params)
		{
			$this->assertFalse(call_user_func_array(array($this->object, $function), $params));
		}
	}

	/**
	 * Tests that all functions properly return Exceptions
	 */
	public function testExceptions()
	{
		$this->http->expects($this->atLeastOnce())->method('get')->will($this->returnCallback(array($this, 'picasaExceptionCallback')));
		$this->http->expects($this->atLeastOnce())->method('post')->will($this->returnCallback(array($this, 'picasaDataExceptionCallback')));

		$functions['listAlbums'] = array('userID');
		$functions['createAlbum'] = array('userID', 'New Title', 'private');
		$functions['getAlbum'] = array('https://picasaweb.google.com/data/entry/api/user/12345678901234567890/albumid/0123456789012345678');

		foreach ($functions as $function => $params)
		{
			$exception = false;

			try
			{
				call_user_func_array(array($this->object, $function), $params);
			}
			catch (\UnexpectedValueException $e)
			{
				$exception = true;
				$this->assertEquals($e->getMessage(), 'Unexpected data received from Google: `BADDATA`.');
			}
			$this->assertTrue($exception);
		}
	}
}
