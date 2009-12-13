<?php
/**
 * @version		$Id$
 * @copyright   Copyright (C) 2007 - 2009 Joomla! Vargas. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Guillermo Vargas (guille@vargas.co.cr)
 */

// no direct access
defined('_JEXEC') or die;



/**
 * @package	     Xmap
 * @subpackage  com_xmap
 * @since	       2.0
 */
class XmapControllerExtension extends JController
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->registerTask('save2copy',	'save');
		$this->registerTask('save2new',	 'save');
		$this->registerTask('apply',	    'save');
	}

	/**
	 * Display the view
	 */
	function display()
	{
	}

	/**
	 * Proxy for getModel
	 */
	function &getModel()
	{
		return parent::getModel('Extension', '', array('ignore_request' => true));
	}

	/**
	 * Method to add a new extension.
	 *
	 * @return      void
	 */
	public function add()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Clear the menu item edit information from the session.
		$app->setUserState('com_xmap.edit.extension.id',       null);
		$app->setUserState('com_xmap.edit.extension.data',     null);

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extension&layout=edit', false));
	}
	/**
	 * Method to edit a object
	 *
	 * Sets object ID in the session from the request, checks the item out, and then redirects to the edit page.
	 *
	 * @access      public
	 * @return      void
	 */
	function edit()
	{
		// Initialize variables.
		$app    = &JFactory::getApplication();
		$ids    = JRequest::getVar('cid', array(), '', 'array');

		// Get the id of the group to edit.
		$id =  (empty($ids) ? JRequest::getInt('extension_id') : (int) array_pop($ids));

		// Get the previous row id (if any) and the current row id.
		$previousId     = (int) $app->getUserState('com_xmap.edit.extension.id');
		$app->setUserState('com_xmap.edit.extension.id', $id);

		// Get the menu item model.
		$model = &$this->getModel();


		// Check-out succeeded, push the new row id into the session.
		$app->setUserState('com_xmap.edit.extension.id',       $id);
		$app->setUserState('com_xmap.edit.extension.data',     null);

		$this->setRedirect('index.php?option=com_xmap&view=extension&layout=edit');

		return true;
	}
	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item ID in the session to null, and then redirects to the list page.
	 *
	 * @access	public
	 * @return	void
	 */
	function cancel()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Clear the menu item edit information from the session.
		$app->setUserState('com_xmap.edit.extension.id',	null);
		$app->setUserState('com_xmap.edit.extension.data',	null);

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extensions', false));
	}
	/**
	 * Save the record
	 */
	function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialize variables.
		$app    = &JFactory::getApplication();
		$model  = &$this->getModel('Extension');
		$task   = $this->getTask();

		// Get posted form variables.
		$data	   = JRequest::getVar('jform', array(), 'post', 'array');
var_dump($data);
exit;

		// Populate the row id from the session.
		$data['extension_id'] = (int) $app->getUserState('com_xmap.edit.extension.id');

		// Validate the posted data.
		$form   = &$model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data   = $model->validate($form, $data);
		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				}
				else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_xmap.edit.extension.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extension&layout=edit', false));
			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState('com_xmap.edit.extension.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JError_Save_failed', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extension&layout=edit', false));
			return false;
		}

		$this->setMessage(JText::_('JController_Save_success'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$app->setUserState('com_xmap.edit.extension.id',       $model->getState('extension.id'));
				$app->setUserState('com_xmap.edit.extension.data',     null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extension&layout=edit', false));
				break;

			case 'save2new':
				// Clear the row id and data in the session.
				$app->setUserState('com_xmap.edit.extension.id',       null);
				$app->setUserState('com_xmap.edit.extension.data',     null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extension&layout=edit', false));
				break;

			default:
				// Clear the row id and data in the session.
				$app->setUserState('com_xmap.edit.extension.id',       null);
				$app->setUserState('com_xmap.edit.extension.data',     null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=com_xmap&view=extensions', false));
				break;
		}
	}
	/**
	 * Removes an item
	 */
	function delete()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		// Get items to remove from the request.
		$cid    = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_('Select an item to delete'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if (!$model->delete($cid)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_xmap&view=extensions');
	}
}
