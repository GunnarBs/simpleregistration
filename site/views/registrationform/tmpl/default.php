<?php defined('_JEXEC') or die('Restricted access'); 
/**
* @version		  $Id: views/registrationform/tmpl/default.php 2012-10-09 19:40:00Z zanardi $
* @package		  GiBi SimpleRegistration
* @author       GiBiLogic 
* @authorEmail  info@gibilogic.com
* @authorUrl    http://www.gibilogic.com
* @copyright	  Copyright (C) 2011-2012 GiBiLogic snc. All rights reserved.
* @license		  GNU/GPL v2 or later
*/
?>
<script language="javascript" type="text/javascript" src="includes/js/joomla.javascript.js"></script>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	varEmail1 = $('email').value;

	// do field validation
	if ( varEmail1 == "" ) {
		alert( "<?php echo JText::_( 'ERROR_EMAIL_MISSING', true ); ?>" );
	} else if (! document.formvalidator.isValid(form) ) {
		var msg = 'Some values are not acceptable.  Please retry.';
		if($('email').hasClass('invalid')){msg += '\n\n\t* Invalid E-Mail Address';}
		alert(msg);
	} else {
		submitform( pressbutton );
	}
}

</script>

<style type="text/css">
	.invalid {border: red;color:red;}
</style>

<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>
<?php endif; ?>

<!-- div names and label classes are equivalent to those of com_user registration form -->
<div class="joomla">
	<div class="user">
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
			<div>
				<label class="label-left" for="email"><?php echo JText::_( 'EMAIL' ); ?>: </label>
				<input type="text" name="email" id="email" class="validate-email">
			</div>
      <?php if( $this->params->get('requestpassword') ) : ?>
        <div>
          <label class="label-left" for="password"><?php echo JText::_( 'PASSWORD' ); ?>: </label>
          <input type="password" name="password" id="password">
        </div>
      <?php endif ?>
			<div>
				<button type="button" onclick="submitbutton('save')">
					<?php echo JText::_('Save') ?>
				</button>
			</div>
			<input type="hidden" name="option" value="com_simpleregistration" />
			<input type="hidden" name="task" value="" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>
