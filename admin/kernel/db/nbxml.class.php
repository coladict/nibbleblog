<?php

/*
 * Nibbleblog -
 * http://www.nibbleblog.com
 * Author Diego Najar

 * All Nibbleblog code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
*/

class NBXML extends SimpleXMLElement {

	// Private keys = array('username' => 'diego');
	public function addGodChild($name, $private_key) {
		// Add and scape &
		$node = parent::addChild($name);
		$node[0] = ''; // (BUG) Con esta forma escapamos el & que no escapa el addChild

		foreach ($private_key as $name => $value) {
			$node->addAttribute($name, $value);
		}

		return $node;
	}

	public function addChild($name, $value='', $namespace='') {
		// Get type of the value will be insert
		$type	= gettype($value);

		// Add and scape &
		$node = parent::addChild($name);
		$node[0] = $value; // (BUG) Con esta forma escapamos el & que no escapa el addChild

		// Add type
		$node->addAttribute('type', $type);

		return $node;
	}

	public function addAttribute($name, $value='', $namespace='') {
		return parent::addAttribute($name, $value);
	}

	public function getAttribute($name) {
		return (string)$this->attributes()->{$name};
	}

	public function setChild($name, $value) {
		if (isset($this->{$name})) {
			$this->{$name} = $value;
		}

		return false;
	}

	public function getChild($name) {
		$type = @$this->{$name}->getAttribute('type');
		$value = (string)$this->{$name};

		return empty($type) ? $value : $this->cast($type, $value);
	}

	public function is_set($name) {
		return isset($this->{$name});
	}

	public function cast($type, $data) {
		if ($type == 'string') {
			return (string) $data;
		}
		else if (($type =='int') || ($type == 'integer')) {
			return (int) $data;
		}
		else if (($type == 'bool') || ($type == 'boolean')) {
			return (bool) $data;
		}
		else if ($type == 'float') {
			return (float) $data;
		}
		else if ($type == 'array') {
			return (array) $data;
		}
		else if ($type == 'object') {
			return (object) $data;
		}

		return $data;
	}

}
