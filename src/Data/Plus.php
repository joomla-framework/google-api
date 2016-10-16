<?php
/**
 * Part of the Joomla Framework Google Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Data;

use Joomla\Google\Auth;
use Joomla\Google\Data;
use Joomla\Registry\Registry;

/**
 * Google+ data class for the Joomla Framework.
 *
 * @property-read  Plus\Activities  $activities  Google+ API object for activities.
 * @property-read  Plus\Comments    $comments    Google+ API object for comments.
 * @property-read  Plus\People      $people      Google+ API object for people.
 *
 * @since  1.0
 */
class Plus extends Data
{
	/**
	 * Google+ API object for activities.
	 *
	 * @var    Plus\Activities
	 * @since  1.0
	 */
	protected $activities;

	/**
	 * Google+ API object for comments.
	 *
	 * @var    Plus\Comments
	 * @since  1.0
	 */
	protected $comments;

	/**
	 * Google+ API object for people.
	 *
	 * @var    Plus\People
	 * @since  1.0
	 */
	protected $people;

	/**
	 * Constructor.
	 *
	 * @param   array|\ArrayAccess  $options  Google options object
	 * @param   Auth                $auth     Google data http client object
	 *
	 * @since   1.0
	 */
	public function __construct($options = array(), Auth $auth = null)
	{
		// Setup the default API url if not already set.
		$options->def('api.url', 'https://www.googleapis.com/plus/v1/');

		parent::__construct($options, $auth);

		if (isset($this->auth) && !$this->auth->getOption('scope'))
		{
			$this->auth->setOption('scope', 'https://www.googleapis.com/auth/plus.me');
		}
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException If $name is not a valid sub class.
	 */
	public function __get($name)
	{
		$class = 'Joomla\\Google\\Data\\Plus\\' . ucfirst($name);

		if (class_exists($class))
		{
			if (false == isset($this->$name))
			{
				$this->$name = new $class($this->options, $this->auth);
			}

			return $this->$name;
		}

		throw new \InvalidArgumentException(sprintf('Argument %s produced an invalid class name: %s', $name, $class));
	}
}
