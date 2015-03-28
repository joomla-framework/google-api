<?php
/**
 * Part of the Joomla Framework Google Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google;

/**
 * Joomla Framework class for interacting with the Google APIs.
 *
 * @since  1.0
 */
class Google
{
	/**
	 * Options for the Google object.
	 *
	 * @var    array|\ArrayAccess
	 * @since  1.0
	 */
	protected $options;

	/**
	 * The authentication client object to use in sending authenticated HTTP requests.
	 *
	 * @var    Auth
	 * @since  1.0
	 */
	protected $auth;

	/**
	 * Google API object for data request.
	 *
	 * @var    Data
	 * @since  1.0
	 */
	protected $data;

	/**
	 * Google API object for embed generation.
	 *
	 * @var    Embed
	 * @since  1.0
	 */
	protected $embed;

	/**
	 * Constructor.
	 *
	 * @param   array|\ArrayAccess  $options  Google options object.
	 * @param   Auth                $auth     The authentication client object.
	 *
	 * @since   1.0
	 */
	public function __construct($options = array(), Auth $auth = null)
	{
		if (!is_array($options) && !($options instanceof \ArrayAccess))
		{
			throw new \InvalidArgumentException(
				'The options param must be an array or implement the ArrayAccess interface.'
			);
		}

		$this->options = $options;
		$this->auth    = isset($auth) ? $auth : new Auth\OAuth2($this->options);
	}

	/**
	 * Method to create Data objects
	 *
	 * @param   string  $name     Name of property to retrieve
	 * @param   array   $options  Google options object.
	 * @param   Auth    $auth     The authentication client object.
	 *
	 * @return  Data  Google data API object.
	 *
	 * @since   1.0
	 */
	public function data($name, $options = null, $auth = null)
	{
		if ($this->options && !$options)
		{
			$options = $this->options;
		}

		if ($this->auth && !$auth)
		{
			$auth = $this->auth;
		}

		switch (strtolower($name))
		{
			case 'plus':
				return new Data\Plus($options, $auth);

			case 'picasa':
				return new Data\Picasa($options, $auth);

			case 'adsense':
				return new Data\Adsense($options, $auth);

			case 'calendar':
				return new Data\Calendar($options, $auth);

			default:
				throw new \InvalidArgumentException("Invalid data object type '$name'.");
		}
	}

	/**
	 * Method to create Embed objects
	 *
	 * @param   string  $name     Name of property to retrieve
	 * @param   array   $options  Google options object.
	 *
	 * @return  Embed  Google embed API object.
	 *
	 * @since   1.0
	 */
	public function embed($name, $options = null)
	{
		if ($this->options && !$options)
		{
			$options = $this->options;
		}

		switch (strtolower($name))
		{
			case 'maps':
				return new Embed\Maps($options);

			case 'analytics':
				return new Embed\Analytics($options);

			default:
				throw new \InvalidArgumentException("Invalid embed object type '$name'.");
		}
	}

	/**
	 * Get an option from the Google instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Set an option for the Google instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Google  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}
}
