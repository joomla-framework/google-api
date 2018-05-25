<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests;

use Joomla\Google\Google;

/**
 * Test class for \Joomla\Google\Google.
 */
class GoogleTest extends GoogleTestCase
{
	/**
	 * Object under test.
	 *
	 * @var  Google
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

		$this->object = new Google($this->options, $this->auth);
	}

	/**
	 * Tests the magic __get method - data
	 */
	public function test__GetData()
	{
		$this->options->set('clientid', '1075367716947.apps.googleusercontent.com');
		$this->options->set('redirecturi', 'http://j.aaronschmitz.com/web/calendar-test');
		$this->assertThat(
			$this->object->data('Plus'),
			$this->isInstanceOf('Joomla\Google\Data\Plus')
		);
		$this->assertThat(
			$this->object->data('Picasa'),
			$this->isInstanceOf('Joomla\Google\Data\Picasa')
		);
		$this->assertThat(
			$this->object->data('Adsense'),
			$this->isInstanceOf('Joomla\Google\Data\Adsense')
		);
		$this->assertThat(
			$this->object->data('Calendar'),
			$this->isInstanceOf('Joomla\Google\Data\Calendar')
		);
	}

	/**
	 * Tests the magic __get method - embed
	 */
	public function test__GetEmbed()
	{
		$this->assertThat(
			$this->object->embed('Maps'),
			$this->isInstanceOf('Joomla\Google\Embed\Maps')
		);
		$this->assertThat(
			$this->object->embed('Analytics'),
			$this->isInstanceOf('Joomla\Google\Embed\Analytics')
		);
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
}
