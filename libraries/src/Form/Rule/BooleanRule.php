<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Form\Rule;

use Joomla\CMS\Form\FormRule;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Form Rule class for the Joomla Platform.
 *
 * @since  1.7.0
 */
class BooleanRule extends FormRule
{
    /**
     * The regular expression to use in testing a form field value.
     *
     * @var    string
     * @since  1.7.0
     */
    protected $regex = '^(?:[01]|true|false)$';

    /**
     * The regular expression modifiers to use when testing a form field value.
     *
     * @var    string
     * @since  1.7.0
     */
    protected $modifiers = 'i';
}
