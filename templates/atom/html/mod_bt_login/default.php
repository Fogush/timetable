<?php
/**
 * @package 	mod_bt_login - BT Login Module
 * @version		2.4.4
 * @created		April 2012
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div id="btl"> 
  <!-- Panel top -->
  <div class="btl-links">
    <?php if($type == 'logout') : ?>
    <!-- Profile button --> 
    <span id="btl-panel-profile">
    <?php
			echo JText::_("BTL_WELCOME").", ";
			if($params->get('name') == 0) : {
				echo $user->get('name');
			} else : {
				echo $user->get('username');
			} endif;
			?>
    </span>
    <?php else : ?>
    <!-- Login button --> 
    <a href="#" id="btl-panel-login" class=" <?php echo $effect;?>"> <?php echo JText::_('JLOGIN');?></a> 
    
    <!-- Registration button -->
    <?php
			if($enabledRegistration){
				$option = JRequest::getCmd('option');
				$task = JRequest::getCmd('task');
				if($option!='com_user' && $task != 'register' ){
			?>
    <a href="#" id="btl-panel-registration" class="<?php echo $effect;?>"> <?php echo JText::_('JREGISTER');?></a>
    <?php }
			} ?>
    <?php endif; ?>
  </div>
  <!-- content dropdown/modal box -->
  <div id="btl-content">
    <?php if($type == 'logout') { ?>
    <!-- Profile module -->
    <div id="btl-content-profile" class="btl-content-block">
      <div id="module-in-profile"> <?php echo $loggedInHtml; ?> </div>
      <?php if($showLogout == 1):?>
      <div class="btl-buttonsubmit">
        <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="logoutForm">
          <button name="Submit" class="btl-buttonsubmit" onclick="document.logoutForm.submit();"><?php echo JText::_('JLOGOUT'); ?></button>
          <input type="hidden" name="option" value="com_users" />
          <input type="hidden" name="task" value="user.logout" />
          <input type="hidden" name="return" value="<?php echo $return; ?>" />
          <?php echo JHtml::_('form.token'); ?>
        </form>
      </div>
      <?php endif;?>
    </div>
    <?php }else{ ?>
    <!-- Form login -->
    <div id="btl-content-login" class="btl-content-block">
      <?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
      <?php JHTML::_('script', 'openid.js'); ?>
      <?php endif; ?>
      
      <!-- if not integrated any component -->
      <?php if($integrated_com==''|| $moduleRender == ''){?>
      <form name="btl-formlogin" class="btl-formlogin" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post">
        <div id="btl-login-in-process"></div>
        <h2><?php echo JText::_('USER_LOGIN') ?></h2>
        <?php if ($enabledRegistration) : ?>
        <strong><?php echo sprintf(JText::_('DONT_HAVE_AN_ACCOUNT_YET'),'<a href="'.JRoute::_('index.php?option=com_users&view=registration').'">','</a>');?></strong>
        <div class="space15"></div>
        <?php endif; ?>
        <div class="btl-error" id="btl-login-error"></div>
        <div class="input-prepend"> <span class="add-on icon-user"></span> 
          <!--<div class="btl-label"><?php echo JText::_('MOD_BT_LOGIN_USERNAME') ?></div>-->
          
          <input id="btl-input-username" class="span2" type="text" name="username" onblur="if(this.value=='') this.value='User Name';" onfocus="if(this.value=='User Name') this.value='';" value="User Name"	/>
        </div>
        <div class="input-prepend"> 
          <!--<div class="btl-label"><?php echo JText::_('MOD_BT_LOGIN_PASSWORD') ?></div>--> 
          <span class="add-on icon-lock"></span>
          <input id="btl-input-password" type="password" name="password" alt="password" onblur="if(this.value=='') this.value='Password';" onfocus="if(this.value=='Password') this.value='';" value="Password" />
        </div>
        <div class="clear"></div>
        <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
        <div class="gantry-left">
          <div id="btl-input-remember">
            <label class="checkbox">
              <input id="btl-checkbox-remember"  type="checkbox" name="remember"
							value="yes" />
              <?php echo JText::_('BT_REMEMBER_ME'); ?> </label>
          </div>
        </div>
        <?php endif; ?>
        <div class="gantry-right">
          <input type="submit" name="Submit" class="btn" onclick="return loginAjax()" value="<?php echo JText::_('JLOGIN') ?>" />
          <input type="hidden" name="option" value="com_users" />
          <input type="hidden" name="task" value="user.login" />
          <input type="hidden" name="return" id="btl-return"	value="<?php echo $return; ?>" />
          <?php echo JHtml::_('form.token');?> </div>
      </form>
      <div class="clear"></div>
      <div class="space15"></div>
      <div class="seprator2"></div>
      <div class="space15"></div>
      <div id="bt_ul"> <span> <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"> <?php echo JText::_('BT_FORGOT_YOUR_PASSWORD'); ?></a> </span>&nbsp;&nbsp;|&nbsp;&nbsp; <span> <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"> <?php echo JText::_('BT_FORGOT_YOUR_USERNAME'); ?></a> </span> </div>
      
      <!-- if integrated with one component -->
      <?php }else{ ?>
      <h2><?php echo JText::_('JLOGIN') ?></h2>
      <div id="btl-wrap-module">
        <?php  echo $moduleRender; ?>
      </div>
      <?php }?>
    </div>
    <?php if($enabledRegistration ){ ?>
    <div id="btl-content-registration" class="btl-content-block"> 
      <!-- if not integrated any component -->
      <?php if($integrated_com==''){?>
      <form name="btl-formregistration"  autocomplete="off">
        <div id="btl-register-in-process"></div>
        <h2><?php echo JText::_('CREATE_AN_ACCOUNT') ?></h2>
        <div id="btl-success"></div>
        <strong><?php echo JText::_("BTL_REQUIRED_FIELD"); ?></strong>
        <div class="space15"></div>
        <div id="btl-registration-error" class="btl-error"></div>
        <label><?php echo JText::_( 'MOD_BT_LOGIN_NAME' ); ?></label>
        <input id="btl-input-name" type="text" name="jform[name]"  />
        <div id="btl-registration-name-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <label><?php echo JText::_( 'MOD_BT_LOGIN_USERNAME' ); ?></label>
        <input id="btl-input-username1" type="text" name="jform[username]"  />
        <div id="btl-registration-username-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <label><?php echo JText::_( 'MOD_BT_LOGIN_PASSWORD' ); ?></label>
        <input id="btl-input-password1" type="password" name="jform[password1]"  />
        <div id="btl-registration-pass1-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <label><?php echo JText::_( 'MOD_BT_VERIFY_PASSWORD' ); ?></label>
        <input id="btl-input-password2" type="password" name="jform[password2]"  />
        <div id="btl-registration-pass2-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <label><?php echo JText::_( 'MOD_BT_EMAIL' ); ?></label>
        <input id="btl-input-email1" type="text" name="jform[email1]" />
        <div id="btl-registration-email1-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <label><?php echo JText::_( 'MOD_BT_VERIFY_EMAIL' ); ?></label>
        <input id="btl-input-email2" type="text" name="jform[email2]" />
        <div id="btl-registration-email2-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <!-- add captcha-->
        <?php if($enabledRecaptcha=='recaptcha'){?>
        <div class="btl-field">
          <div class="btl-label"><?php echo JText::_( 'MOD_BT_CAPTCHA' ); ?></div>
          <div  id="recaptcha"><?php echo $reCaptcha;?></div>
        </div>
        <div id="btl-registration-captcha-error" class="btl-error-detail"></div>
        <div class="clear"></div>
        <!--  end add captcha -->
        <?php }?>
        <div class="space15"></div>
        <div class="seprator2"></div>
        <div class="space15"></div>
        <div class="btl-buttonsubmit">
          <button type="submit" class="btn btn-primary" onclick="return registerAjax()" > <?php echo JText::_('JREGISTER');?> </button>
          <input type="hidden" name="task" value="register" />
          <?php echo JHtml::_('form.token');?> </div>
      </form>
      <!-- if  integrated any component -->
      <?php }else{ ?>
      <h2><?php echo JText::_("JREGISTER"); ?></h2>
      <iframe id="btl-iframe" width="850" height="500" frameborder="0" name="btl-iframe" src="<?php echo $linkOption?>"  ></iframe>
      <?php }?>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
  <div class="clear"></div>
</div>
<script type="text/javascript">
	var btlOpt = 
		{
			REQUIRED_FILL_ALL		: '<?php echo addslashes(JText::_("REQUIRED_FILL_ALL")); ?>',
			BT_AJAX					: '<?php echo JURI::getInstance()->toString(); ?>',
			BT_RETURN				: '<?php echo $return_decode; ?>',
			E_LOGIN_AUTHENTICATE	: "<?php echo addslashes(JText::_("E_LOGIN_AUTHENTICATE")); ?>",
			REQUIRED_NAME			:'<?php echo addslashes(JText::_("REQUIRED_NAME")); ?>',
			REQUIRED_USERNAME		: '<?php echo addslashes(JText::_("REQUIRED_USERNAME")); ?>',
			REQUIRED_PASSWORD		:'<?php echo addslashes(JText::_("REQUIRED_PASSWORD")); ?>',
			REQUIRED_VERIFY_PASSWORD:'<?php echo addslashes(JText::_("REQUIRED_VERIFY_PASSWORD")); ?>',
			PASSWORD_NOT_MATCH		:'<?php echo addslashes(JText::_("PASSWORD_NOT_MATCH")); ?>',
			REQUIRED_EMAIL			:'<?php echo addslashes(JText::_("REQUIRED_EMAIL")); ?>',
			EMAIL_INVALID			:'<?php echo addslashes(JText::_("EMAIL_INVALID")); ?>',	
			REQUIRED_VERIFY_EMAIL	:'<?php echo addslashes(JText::_("REQUIRED_VERIFY_EMAIL")); ?>',
			EMAIL_NOT_MATCH			:'<?php echo addslashes(JText::_("EMAIL_NOT_MATCH")); ?>',
			RECAPTCHA				:'<?php echo $enabledRecaptcha ;?>',
			LOGIN_TAGS				:'<?php echo $loginTag?>',
			REGISTER_TAGS			:'<?php echo $registerTag?>',
			EFFECT					:'<?php echo $effect?>',
			ALIGN					:'<?php echo $align?>',
			WIDTH_REGISTER_PANEL	: BTLJ("#btl-content-registration").outerWidth(),
			BG_COLOR				:'<?php echo $bgColor ;?>',
			MOUSE_EVENT				:'<?php echo $params->get('mouse_event','click') ;?>',
			TEXT_COLOR				:'<?php echo $textColor;?>'
		}
		if(btlOpt.ALIGN == "center"){
			BTLJ(".btl-panel").css('textAlign','center');
			BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").each(function(){
				var panelid = "#"+this.id.replace("content","panel");
				var content = this;
				BTLJ(window).load(function(){
				var left =	BTLJ(panelid).offset().left - jQuery('#btl').offset().left - (BTLJ(content).outerWidth()/2)+ (BTLJ(panelid).outerWidth()/2);
					BTLJ(content).css('left',left );
				})
			})
		}else{
			BTLJ(".btl-panel").css('float',btlOpt.ALIGN);
			BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").css(btlOpt.ALIGN,0);
			BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").css('top',BTLJ(".btl-panel").height()+1);
		}
		BTLJ("input.btl-buttonsubmit,button.btl-buttonsubmit, #btl .btl-panel > span")
		.css("background-color",btlOpt.BG_COLOR)
		.css("color",btlOpt.TEXT_COLOR);
		BTLJ("#btl .btl-panel > span").css("border",btlOpt.TEXT_COLOR);
</script> 
