<?php

namespace Deval;

class ConcatBlock implements Block
{
	public function __construct ($blocks)
	{
		$this->blocks = $blocks;
	}

	public function compile ($generator, $expressions, &$variables)
	{
		if (count ($this->blocks) < 1)
			return new Output ();

		$output = $this->blocks[0]->compile ($generator, $expressions, $variables);

		for ($i = 1; $i < count ($this->blocks); ++$i)
			$output->append ($this->blocks[$i]->compile ($generator, $expressions, $variables));

		return $output;
	}

	public function resolve ($blocks)
	{
		$results = array ();

		foreach ($this->blocks as $block)
			$results[] = $block->resolve ($blocks);

		return new self ($results);
	}

	public function wrap ($caller)
	{
		$results = array ();

		foreach ($this->blocks as $block)
			$results[] = $block->wrap ($caller);

		return new self ($results);
	}
}

?>
