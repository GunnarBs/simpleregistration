<?php
/**
* @version		  frontend/views/registrationform/tmpl/default.php 2014-01-26 20:14:00 UTC zanardi
* @package		  GiBi SimpleRegistration
* @author       GiBiLogic <info@gibilogic.com>
* @authorUrl    http://www.gibilogic.com
* @copyright	  Copyright (C) 2011-2014 GiBiLogic snc. All rights reserved.
* @license		  GNU/GPL v3 or later
*/
defined('_JEXEC') or die();
?>
<div class="registration">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<h1 class="title<?php echo $this->escape($this->params->get('pageclass_sfx','')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>
<?php endif; ?>
	<p><?php echo JText::_('COM_SIMPLEREGISTRATION_NOTE'); ?></p>
	<form action="<?php echo JRoute::_($this->_url.'&task=save') ?>" method="post" name="adminForm" id="adminForm" class="<?php if ($this->params->get('mootools', 1)) echo 'form-validate '; ?>form-horizontal">
		<div class="control-group">
			<div class="control-label">
				<label class="label-left" for="email"><?php echo JText::_('JGLOBAL_EMAIL'); ?>: </label>
			</div>
			<div class="controls">
				<input type="text" name="email" id="email" class="<?php if ($this->params->get('mootools', 1)) echo 'validate-email '; ?>required">
			</div>
		</div>
		<?php if($this->params->get('requestpassword')) : ?>
		<div class="control-group">
			<div class="control-label">
				<label class="label-left" for="password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?>: </label>
			</div>
			<div class="controls">
				<input type="password" name="password" id="password">
			</div>
		</div>
		<?php endif ?>
		<div id="register-button">
			<button id="submit" type="submit" class="button">
				<?php echo JText::_('JREGISTER') ?>
			</button>
		</div>
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>