<?php

$executes = isset ($_GET['executes']) ? (array)json_decode ($_GET['executes'], true) : array ();
$injects = isset ($_GET['injects']) ? (array)json_decode ($_GET['injects'], true) : array ();
$template = isset ($_GET['template']) ? $_GET['template'] : '';

?>
<form method="GET">
	<textarea name="template" rows="8" style="width: 100%;"><?php echo htmlspecialchars ($template); ?></textarea>
	<input name="injects" style="width: 100%;" value="<?php echo htmlspecialchars (json_encode ((object)$injects)); ?>" />
	<input name="executes" style="width: 100%;" value="<?php echo htmlspecialchars (json_encode ((object)$executes)); ?>" />
	<input type="submit" value="OK" />
</form>
<?php

require '../src/deval.php';

if ($template !== '')
{
	echo '<pre>';

	try
	{
		$compiler = new Deval\Compiler (Deval\Block::parse_code ($template));
		$variables = array ();

		echo "original:\n";
		echo '  - source = ' . htmlspecialchars ($compiler->compile (null, $variables)) . "\n";
		echo '  - variables = ' . htmlspecialchars (implode (', ', $variables)) . "\n";

		$compiler->inject ($injects);
		$variables = array ();

		echo "injected:\n";
		echo '  - source = ' . htmlspecialchars ($compiler->compile (null, $variables)) . "\n";
		echo '  - variables = ' . htmlspecialchars (implode (', ', $variables)) . "\n";

		echo "executed:\n";
		echo '  - output = ' . Deval\Evaluator::code ($compiler->compile (), $executes) . "\n";
	}
	catch (Deval\CompileException $exception)
	{
		echo $exception->getMessage ();
	}
	catch (Deval\RuntimeException $exception)
	{
		echo $exception->getMessage ();
	}

	echo '</pre>';
}

?>
