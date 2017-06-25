<?php

namespace Deval;

class ConstantExpression implements Expression
{
	public function __construct ($value)
	{
		$this->value = $value;
	}

	public function __toString ()
	{
		return var_export ($this->value, true);
	}

	public function get_elements (&$elements)
	{
		$elements = array ();

		if (is_array ($this->value) || $this->value instanceof \Traversable)
		{
			foreach ($this->value as $key => $value)
				$elements[$key] = new self ($value);
		}
		else
			$elements[] = new ErrorExpression ($this->value, 'is not iterable');

		return true;
	}

	public function get_value (&$value)
	{
		$value = $this->value;

		return true;
	}

	public function generate ($generator, &$volatiles)
	{
		return Generator::emit_value ($this->value);
	}

	public function inject ($constants)
	{
		return $this;
	}
}

?>
