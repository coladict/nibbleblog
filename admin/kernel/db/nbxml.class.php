<?php

/*
 * Nibbleblog -
 * http://www.nibbleblog.com
 * Author Diego Najar

 * All Nibbleblog code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
*/
$enc = strtolower(mb_internal_encoding());
$internal_is_utf = ($enc == 'utf-8' || $enc == 'utf8');

unset($enc);
class NBXML extends SimpleXMLElement {

	// Private keys = array('username' => 'diego');
	public function addGodChild($name, $private_key) {
		global $internal_is_utf;
		if (!$internal_is_utf) {
			$name = utf8_encode($name);
		}

		// Add and scape &
		$node = parent::addChild($name);
		$node[0] = ''; // (BUG) Con esta forma escapamos el & que no escapa el addChild

		foreach ($private_key as $name => $value) {
			$node->addAttribute($name, $value);
		}

		return $node;
	}

	public function addChild($name, $value='', $namespace='') {
		global $internal_is_utf;
		// Get type of the value will be insert
		$type	= gettype($value);

		// Encode to UTF8
		if (!$internal_is_utf) {
			$name = utf8_encode($name);
			$value = utf8_encode($value);
		}

		// Add and scape &
		$node = parent::addChild($name);
		$node[0] = $value; // (BUG) Con esta forma escapamos el & que no escapa el addChild

		// Add type
		$node->addAttribute('type', $type);

		return $node;
	}

	public function addAttribute($name, $value='', $namespace='') {
		global $internal_is_utf;
		if (!$internal_is_utf) {
			$name = utf8_encode($name);
			$value = utf8_encode($value);
		}

		return parent::addAttribute($name, $value);
	}

	public function getAttribute($name) {
		global $internal_is_utf;
		if ($internal_is_utf) {
			return((string)$this->attributes()->{$name});
		}
		return(utf8_decode((string)$this->attributes()->{$name}));
	}

	public function setChild($name, $value) {
		global $internal_is_utf;
		if (isset($this->{$name})) {
			if ($internal_is_utf) {
				$this->{$name} = $value;
			}
			else {
				$this->{$name} = utf8_encode($value);
			}
		}

		return false;
	}

	public function getChild($name) {
		global $internal_is_utf;
		$type = @$this->{$name}->getAttribute('type');
		if ($internal_is_utf) {
			$value = (string)$this->{$name};
		}
		else {
			$value = utf8_decode((string)$this->{$name});
		}

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
