<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Tests\Data;

use Joomla\Google\Data\Calendar;
use Joomla\Google\Tests\GoogleTestCase;

/**
 * Test class for Joomla\Google\Data\Calendar
 */
class CalendarTest extends GoogleTestCase
{
	/**
	 * Object under test.
	 *
	 * @var  Calendar
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

		$this->object = new Calendar($this->options, $this->auth);

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
	 * Tests the removeCalendar method
	 */
	public function testRemoveCalendar()
	{
		$this->http->expects($this->once())->method('delete')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\emptyCalendarCallback'));
		$result = $this->object->removeCalendar('calendarID');
		$this->assertTrue($result);
	}

	/**
	 * Tests the getCalendar method
	 */
	public function testGetCalendar()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonCalendarCallback'));
		$result = $this->object->getCalendar('calendarID');
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the addCalendar method
	 */
	public function testAddCalendar()
	{
		$this->http->expects($this->once())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$result = $this->object->addCalendar('calendarID', array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the listCalendars method
	 */
	public function testListCalendars()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonCalendarCallback'));
		$result = $this->object->listCalendars(array('option' => 'value'));
		$this->assertEquals($result, array('1' => 1, '2' => 2));
	}

	/**
	 * Tests the editCalendarSettings method
	 */
	public function testEditCalendarSettings()
	{
		$this->http->expects($this->once())->method('put')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$result = $this->object->editCalendarSettings('calendarID', array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the clearCalendar method
	 */
	public function testClearCalendar()
	{
		$this->http->expects($this->once())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\emptyDataCalendarCallback'));
		$result = $this->object->clearCalendar('calendarID');
		$this->assertTrue($result);
	}

	/**
	 * Tests the deleteCalendar method
	 */
	public function testDeleteCalendar()
	{
		$this->http->expects($this->once())->method('delete')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\emptyCalendarCallback'));
		$result = $this->object->deleteCalendar('calendarID');
		$this->assertTrue($result);
	}

	/**
	 * Tests the createCalendar method
	 */
	public function testCreateCalendar()
	{
		$this->http->expects($this->once())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$result = $this->object->createCalendar('Title', array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the editCalendar method
	 */
	public function testEditCalendar()
	{
		$this->http->expects($this->once())->method('put')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$result = $this->object->editCalendar('calendarID', array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the deleteEvent method
	 */
	public function testDeleteEvent()
	{
		$this->http->expects($this->once())->method('delete')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\emptyCalendarCallback'));
		$result = $this->object->deleteEvent('calendarID', 'eventID');
		$this->assertTrue($result);
	}

	/**
	 * Tests the getEvent method
	 */
	public function testGetEvent()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonCalendarCallback'));
		$result = $this->object->getEvent('calendarID', 'eventID', array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the createCalendar method
	 */
	public function testCreateEvent()
	{
		$this->http->expects($this->exactly(9))->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$timezone = new \DateTimeZone('Europe/London');
		$start = new \DateTime('now');
		$end = new \DateTime;
		$end->setTimestamp(time() + 3600)->setTimeZone($timezone);

		$result = $this->object->createEvent('calendarID', time(), time() + 100000, array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', time(), false, array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', false, false, array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', '1-1-2000', '1-2-2012', array('option' => 'value'), false);
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', '1-1-2000', '1-2-2012', array('option' => 'value'), false, true);
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', $start, $end, array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', $start, $end, array('option' => 'value'), true);
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', $start, $end, array('option' => 'value'), 'America/Chicago');
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));

		$result = $this->object->createEvent('calendarID', $start, $end, array('option' => 'value'), $timezone);
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the createEvent method with a bad start date
	 *
	 * @group	JGoogle
	 * @expectedException InvalidArgumentException
	 * @return void
	 */
	public function testCreateEventStartException()
	{
		$this->object->createEvent('calendarID', array(true));
	}

	/**
	 * Tests the createEvent method with a bad end date
	 *
	 * @group	JGoogle
	 * @expectedException InvalidArgumentException
	 * @return void
	 */
	public function testCreateEventEndException()
	{
		$this->object->createEvent('calendarID', time(), array(true));
	}

	/**
	 * Tests the listRecurrences method
	 */
	public function testListRecurrences()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonCalendarCallback'));
		$result = $this->object->listRecurrences('calendarID', 'eventID', array('option' => 'value'));
		$this->assertEquals($result, array('1' => 1, '2' => 2));
	}

	/**
	 * Tests the listEvents method
	 */
	public function testListEvents()
	{
		$this->http->expects($this->once())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonCalendarCallback'));
		$result = $this->object->listEvents('calendarID', array('option' => 'value'));
		$this->assertEquals($result, array('1' => 1, '2' => 2));
	}

	/**
	 * Tests the moveEvent method
	 */
	public function testMoveEvent()
	{
		$this->http->expects($this->once())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$result = $this->object->moveEvent('calendarID', 'eventID', 'newCalendarID');
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
	}

	/**
	 * Tests the editCalendar method
	 */
	public function testEditEvent()
	{
		$this->http->expects($this->once())->method('put')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\jsonDataCalendarCallback'));
		$result = $this->object->editEvent('calendarID', 'eventID', array('option' => 'value'));
		$this->assertEquals($result, array('items' => array('1' => 1, '2' => 2)));
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

		$functions['removeCalendar'] = array('calendarID');
		$functions['getCalendar'] = array('calendarID');
		$functions['addCalendar'] = array('calendarID', array('option' => 'value'));
		$functions['listCalendars'] = array(array('option' => 'value'));
		$functions['editCalendarSettings'] = array('calendarID', array('option' => 'value'));
		$functions['clearCalendar'] = array('calendarID');
		$functions['deleteCalendar'] = array('calendarID');
		$functions['createCalendar'] = array('Title', array('option' => 'value'));
		$functions['editCalendar'] = array('calendarID', array('option' => 'value'));
		$functions['deleteEvent'] = array('calendarID', 'eventID');
		$functions['getEvent'] = array('calendarID', 'eventID', array('option' => 'value'));
		$functions['createEvent'] = array('calendarID', time(), time() + 100000, array('option' => 'value'));
		$functions['listRecurrences'] = array('calendarID', 'eventID', array('option' => 'value'));
		$functions['listEvents'] = array('calendarID', array('option' => 'value'));
		$functions['moveEvent'] = array('calendarID', 'eventID', 'newCalendarID');
		$functions['editEvent'] = array('calendarID', 'eventID', array('option' => 'value'));

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
		$this->http->expects($this->atLeastOnce())->method('get')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\calendarExceptionCallback'));
		$this->http->expects($this->atLeastOnce())->method('delete')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\calendarExceptionCallback'));
		$this->http->expects($this->atLeastOnce())->method('post')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\calendarDataExceptionCallback'));
		$this->http->expects($this->atLeastOnce())->method('put')->will($this->returnCallback('Joomla\\Google\\Tests\\Data\\calendarDataExceptionCallback'));

		$functions['removeCalendar'] = array('calendarID');
		$functions['getCalendar'] = array('calendarID');
		$functions['addCalendar'] = array('calendarID', array('option' => 'value'));
		$functions['listCalendars'] = array(array('option' => 'value'));
		$functions['editCalendarSettings'] = array('calendarID', array('option' => 'value'));
		$functions['clearCalendar'] = array('calendarID');
		$functions['deleteCalendar'] = array('calendarID');
		$functions['createCalendar'] = array('Title', array('option' => 'value'));
		$functions['editCalendar'] = array('calendarID', array('option' => 'value'));
		$functions['deleteEvent'] = array('calendarID', 'eventID');
		$functions['getEvent'] = array('calendarID', 'eventID', array('option' => 'value'));
		$functions['createEvent'] = array('calendarID', time(), time() + 100000, array('option' => 'value'));
		$functions['listRecurrences'] = array('calendarID', 'eventID', array('option' => 'value'));
		$functions['listEvents'] = array('calendarID', array('option' => 'value'));
		$functions['moveEvent'] = array('calendarID', 'eventID', 'newCalendarID');
		$functions['editEvent'] = array('calendarID', 'eventID', array('option' => 'value'));

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

/**
 * Dummy method
 *
 * @param   string   $url      Path to the resource.
 * @param   mixed    $data     Either an associative array or a string to be sent with the request.
 * @param   array    $headers  An array of name-value pairs to include in the header of the request.
 * @param   integer  $timeout  Read timeout in seconds.
 *
 * @return  object
 *
 * @since   1.0
 */
function jsonDataCalendarCallback($url, $data, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/json');
	$response->body = '{"items":{"1":1,"2":2}}';

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
 *
 * @since   1.0
 */
function emptyDataCalendarCallback($url, $data, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'text/html');
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
 *
 * @since   1.0
 */
function jsonCalendarCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/json');
	$response->body = '{"items":{"1":1,"2":2}}';

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
function emptyCalendarCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'text/html');
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
 *
 * @since   1.0
 */
function calendarExceptionCallback($url, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/json');
	$response->body = 'BADDATA';

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
 *
 * @since   1.0
 */
function calendarDataExceptionCallback($url, $data, array $headers = null, $timeout = null)
{
	$response = new \stdClass;

	$response->code = 200;
	$response->headers = array('Content-Type' => 'application/json');
	$response->body = 'BADDATA';

	return $response;
}
