<?php
/**
 * Admin List Edit Tmpl
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
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\String\StringHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::stylesheet('administrator/components/com_fabrik/views/fabrikadmin.css');
HTMLHelper::_('bootstrap.tooltip');
FabrikHelperHTML::formvalidation();
HTMLHelper::_('behavior.keepalive');

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		requirejs(['fab/fabrik'], function (Fabrik) {
			if (task !== 'list.cancel' && !Fabrik.controller.canSaveForm()) {
				window.alert('Please wait - still loading');
				return false;
			}
			if (task == 'list.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
				window.fireEvent('form.save');
				Joomla.submitform(task, document.getElementById('adminForm'));
			} else {
				window.alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
			}
		});
	}
</script>
<form action="<?php Route::_('index.php?option=com_fabrik'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

	<div class="width-40 fltlft">

	<?php

$panels = array(
	array('heading'=>Text::_('COM_FABRIK_DETAILS'),
		'fieldset'=>array('main', 'details2')),

	array('heading'=>Text::_('COM_FABRIK_FILTERS'),
		'fieldset'=>array('main_filter', 'filters')),

	array('heading'=>Text::_('COM_FABRIK_NAVIGATION'),
		'fieldset'=>array('main_nav', 'navigation')),

	array('heading'=>Text::_('COM_FABRIK_LAYOUT'),
		'fieldset'=>array('main_template', 'layout')),

	array('heading'=>Text::_('COM_FABRIK_PDF'),
		'fieldset'=>array('pdf')),

	array('heading'=>Text::_('COM_FABRIK_LINKS'),
		'fieldset' => array('links', 'links2', 'links-fabrik30x')),

	array('heading'=>Text::_('COM_FABRIK_TABS'),
		'fieldset'=>array('tabs')),

	array('heading'=>Text::_('COM_FABRIK_NOTES'),
		'fieldset'=>array('notes')),

	array('heading'=>Text::_('COM_FABRIK_ADVANCED'),
		'fieldset'=>array('advanced'))

);

echo HTMLHelper::_('sliders.start','list-sliders-'.$this->item->id, array('useCookie'=>1));

foreach ($panels as $panel) {
	echo HTMLHelper::_('sliders.panel',$panel['heading'], $panel['fieldset'][0].-'details');
			?>
			<fieldset class="adminform">
				<ul class="adminformlist">
				<?php foreach ($panel['fieldset'] as $fieldset) :
					foreach ($this->form->getFieldset($fieldset) as $field) :?>
					<li>
					<?php if (StringHelper::strtolower($field->type) != 'hidden') {
							echo $field->label;
						} ?>
						<?php echo $field->input; ?>
					</li>
					<?php endforeach;
					endforeach;?>
				</ul>
			</fieldset>
<?php }
echo HTMLHelper::_('sliders.end');
?>

	</div>
	<div class="width-60 fltrt">
		<?php echo HTMLHelper::_('tabs.start', 'list-tabs-'.(int) $this->item->id, array('useCookie'=>1));
		echo $this->loadTemplate('data');
		echo $this->loadTemplate('publishing');
		echo $this->loadTemplate('plugins');
		echo $this->loadTemplate('rules');
		echo HTMLHelper::_('tabs.end'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
