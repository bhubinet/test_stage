<?php
/**
 * Renders a list of Fabrik visualizations
 *
 * @package     Joomla
 * @subpackage  Form
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @since       1.6
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Form\Field\ListField;

require_once JPATH_ADMINISTRATOR . '/components/com_fabrik/helpers/element.php';
//require_once JPATH_SITE . '/components/com_fabrik/helpers/parent.php';

jimport('joomla.form.helper');
FormHelper::loadFieldClass('list');

/**
 * Renders a list of Fabrik visualizations
 *
 * @package     Joomla
 * @subpackage  Form
 * @since       1.6
 */

class JFormFieldVisualizationlist extends ListField
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */

	protected $name = 'Visualizationlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */

	protected function getOptions()
	{
		$a = array(HTMLHelper::_('select.option', '', Text::_('COM_FABRIK_PLEASE_SELECT')));
		$db = FabrikWorker::getDbo(true);
		$query = $db->getQuery(true);
		$query->select('id AS value, label AS text')->from('#__fabrik_visualizations')->where('published = 1')->order('text');
		$db->setQuery($query);
		$elementstypes = $db->loadObjectList();
		$elementstypes = array_merge($a, $elementstypes);

		return $elementstypes;
	}
}
