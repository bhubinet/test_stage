<?php
/**
 * Admin List Import Tmpl
 *
 * @package     Joomla.Administrator
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @since       3.0
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.tooltip');
FabrikHelperHTML::formvalidation();
$app = Factory::getApplication();
$input = $app->input;
?>

<form action="<?php Route::_('index.php?option=com_fabrik'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<?php
	$cid = $input->get('cid', array(), 'array');
	foreach ($cid as $id) : ?>
		<input type="hidden" name="cid[]" value="<?php echo $id ;?>" />
	<?php endforeach; ?>

	<fieldset class="adminform">
		<ul class="adminformlist">
		<?php for ($i = 0; $i < count($this->items); $i++) :?>
  			<li>
  				<?php echo $this->items[$i]?>
  			</li>
		<?php endfor; ?>
		</ul>

		<ul>
		<?php foreach ($this->form->getFieldset('details') as $field) :?>
			<li>
				<?php echo $field->label; ?><?php echo $field->input; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<input type="hidden" name="task" value="" />
  	<?php echo HTMLHelper::_('form.token');
	echo HTMLHelper::_('behavior.keepalive'); ?>
</form>