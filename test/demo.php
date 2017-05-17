<?php

$template = isset ($_GET['template']) ? $_GET['template'] : '';
$variables = isset ($_GET['variables']) ? (array)json_decode ($_GET['variables'], true) : array ();

?>
<form method="GET">
	<textarea name="template" rows="8" style="width: 100%;"><?php echo htmlspecialchars ($template); ?></textarea>
	<input name="variables" style="width: 100%;" value="<?php echo htmlspecialchars (json_encode ((object)$variables)); ?>" />
	<input type="submit" value="OK" />
</form>
<?php

require '../src/deval.php';

if ($template !== '')
{
	try
	{
		$compiler = new Deval\Compiler ($template);
		$variables = array ();

		echo '<pre>';
		echo "original:\n";
		echo '  - source = ' . htmlspecialchars ($compiler->compile ($variables)) . "\n";
		echo '  - variables = ' . htmlspecialchars (implode (', ', $variables)) . "\n";

		$compiler->inject ($variables);
		$variables = array ();

		echo "injected:\n";
		echo '  - source = ' . htmlspecialchars ($compiler->compile ($variables)) . "\n";
		echo '  - variables = ' . htmlspecialchars (implode (', ', $variables)) . "\n";
		echo '</pre>';
	}
	catch (PhpPegJs\SyntaxError $error)
	{
		echo 'Syntax error: ' . $error->getMessage ();
	}
}

?>
