<?php
/**
 * Admin List CSV controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

require_once 'fabcontrollerform.php';

/**
 * Admin List CSV controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  Fabrik
 * @since       3.0.7
 */
class FabrikAdminControllerList extends FabControllerForm
{
    /**
     * The prefix to use with controller messages.
     *
     * @var	string
     */
    protected $text_prefix = 'COM_FABRIK_LIST';

    /**
     * Show the lists data in the admin
     *
     * @return  void
     */
    public function view()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $cid = $input->get('cid', array(0), 'array');
        $cid = $cid[0];
        $cid = $input->getInt('listid', $cid);

        // Grab the model and set its id
        $model = Factory::getApplication()->bootComponent('com_fabrik')->getMVCFactory()->createModel('List', 'FabrikFEModel');
        $model->setState('list.id', $cid);
        $viewType = Factory::getDocument()->getType();

        // Use the front end list renderer
        $this->setPath('view', COM_FABRIK_FRONTEND . '/views');
        $viewLayout	= $input->get('layout', 'default');
        $view = $this->getView($this->view_item, $viewType, 'FabrikView');
        $view->setModel($model, true);

        // Set the layout
        $view->setLayout($viewLayout);
        ToolBarHelper::title(Text::_('COM_FABRIK_MANAGER_LISTS'), 'list');
        $view->display();
    }
}