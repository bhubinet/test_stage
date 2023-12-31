<?php
/**
 * Admin List Edit:related data Tmpl
 *
 * @package     Joomla.Administrator
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @since       3.0
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

?>
<fieldset>
	<legend>
		<?php echo HTMLHelper::_('tooltip', Text::_('COM_FABRIK_RELATED_DATA_DESC', false), Text::_('COM_FABRIK_RELATED_DATA'), 'tooltip.png', Text::_('COM_FABRIK_RELATED_DATA'));?>
	</legend>
	<ul class="adminformlist">
	<?php foreach ($this->form->getFieldset('facetedlinks2') as $field):
		?>
		<li><?php echo $field->label; ?>
		<?php echo $field->input; ?>
		</li>
	<?php
	endforeach;
	?>
	</ul>
	<div style="clear:both"></div>
	<?php foreach ($this->form->getFieldset('facetedlinks') as $field): ?>
		<?php echo $field->input; ?>
	<?php endforeach; ?>

</fieldset>