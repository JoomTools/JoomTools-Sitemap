<?php
/**
 * @version             $Id$
 * @copyright		Copyright (C) 2007 - 2009 Joomla! Vargas. All rights reserved.
 * @license             GNU General Public License version 2 or later; see LICENSE.txt
 * @author              Guillermo Vargas (guille@vargas.co.cr)
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

jimport('joomla.html.pane');
$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'extension.cancel' || document.formvalidator.isValid($('item-form'))) {
			<?php //echo $this->form->fields['introtext']->editor->save('jform[introtext]'); ?>
			submitform(task);
		}
	}
// -->
</script>

<form action="<?php JRoute::_('index.php?option=com_xmap'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<div class="width-40" style="float:left">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
                <tr>
                        <td width="100" class="key"><?php echo $this->form->getLabel('name'); ?></td>
			<td><?php echo $this->form->getInput('name'); ?></td>
		</tr>

		<tr>
                        <td width="100" class="key"><label><?php echo JText::_( 'Author' ); ?></label></td>
                        <td><?php echo $this->extension->author; ?></td>
                </tr>

		<tr>
                        <td width="100" class="key"><label><?php echo JText::_( 'Author_Email' ); ?></label></td>
                        <td><?php echo $this->extension->authorEmail; ?></td>
		</tr>

		<tr>
                        <td width="100" class="key"><label><?php echo JText::_( 'Author_Website' ); ?></label></td>
                        <td><?php echo $this->extension->authorUrl; ?></td>
                </tr>

		<tr>
                        <td width="100" class="key"><label><?php echo JText::_( 'Description' ); ?></label></td>
                        <td><?php echo $this->extension->description; ?></td>
                </tr>

		</table>

	</fieldset>
	</div>

	<div class="width-60" style="float:left">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Parameters' ); ?></legend>
	<?php	if($output = $this->params->render('params')) :
			echo $output;
		else:
			echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('There are no parameters for this item')."</div>";
		endif;
	?>
	</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
