jQuery.noConflict();
if(typeof(BTLJ)=='undefined') var BTLJ = jQuery;
if(typeof(btTimeOut)=='undefined') var btTimeOut;
if(typeof(requireRemove)=='undefined') var requireRemove = true;

var mobile = function(){
	return {
		detect:function(){
			var uagent = navigator.userAgent.toLowerCase(); 
			var list = this.mobiles;
			var ismobile = false;
			for(var d=0;d<list.length;d+=1){
				if(uagent.indexOf(list[d])!=-1){
					ismobile = true;
				}
			}
			return ismobile;
		},
		mobiles:[
			"midp","240x320","blackberry","netfront","nokia","panasonic",
			"portalmmm","sharp","sie-","sonyericsson","symbian",
			"windows ce","benq","mda","mot-","opera mini",
			"philips","pocket pc","sagem","samsung","sda",
			"sgh-","vodafone","xda","palm","iphone",
			"ipod","android"
		]
	};
}();
var autoPos = mobile.detect() == false; 
var mobilePopupPos = {top:0,right:0}; // Position of popup

BTLJ(document).ready(function() {
	
	//set position
	//BTLJ("#btl-success").css("width",btlOpt.WIDTH_REGISTER_PANEL);
	if(btlOpt.ALIGN == "center"){
		BTLJ(".btl-panel").css('textAlign','center');
		BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").each(function(){
			var panelid = "#"+this.id.replace("content","panel");
			var left = BTLJ(panelid).offset().left - jQuery('#btl').offset().left - (BTLJ(this).width()/2)+ (BTLJ(panelid).outerWidth()/2);
			if(left < 0) left = 0;
			BTLJ(this).css('left',left );
		})
	}else{
		BTLJ(".btl-panel").css('float',btlOpt.ALIGN);
		BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").css(btlOpt.ALIGN,0);
		//BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").css('top',BTLJ(".btl-panel").height()+3);
	}	
	BTLJ("#btl-content #btl-content-login,#btl-content #btl-content-registration, #btl-content #btl-content-profile").css('top',BTLJ(".btl-panel").height()+1);	
	BTLJ(btlOpt.LOGIN_TAGS).addClass("btl-modal");
	if(btlOpt.REGISTER_TAGS != ''){
		BTLJ(btlOpt.REGISTER_TAGS).addClass("btl-modal");
	}

	// Login event
	var elements = '#btl-panel-login';
	if (btlOpt.LOGIN_TAGS) elements += ', ' + btlOpt.LOGIN_TAGS;
	if (btlOpt.MOUSE_EVENT =='click'){ 
		BTLJ(elements).click(function (event) {
				showLoginForm();
				event.preventDefault();
		});	
	}else{
		BTLJ(elements).hover(function () {
				showLoginForm();
		},function(){});
	}

	// Registration/Profile event
	elements = '#btl-panel-registration';
	if (btlOpt.REGISTER_TAGS) elements += ', ' + btlOpt.REGISTER_TAGS;
	if (btlOpt.MOUSE_EVENT =='click'){ 
		BTLJ(elements).click(function (event) {
			showRegistrationForm();
			event.preventDefault();
		});	
		BTLJ("#btl-panel-profile").click(function(event){
			showProfile();
			event.preventDefault();
		});
	}else{
		BTLJ(elements).hover(function () {
				showRegistrationForm();
		},function(){});
		BTLJ("#btl-panel-profile").hover(function () {
				showProfile();
		},function(){});
	}
	BTLJ('#register-link a').click(function (event) {
			if(BTLJ('.btl-modal').length){
				BTLJ.modal.close();setTimeout("showRegistrationForm();",1000);
			}
			else{
				showRegistrationForm();
			}
			event.preventDefault();
	});	
	
	// Close form
	BTLJ(document).click(function(event){
		if(requireRemove && event.which == 1) btTimeOut = setTimeout('BTLJ("#btl-content > div").slideUp();BTLJ(".btl-panel span").removeClass("active");',10);
		requireRemove =true;
	})
	BTLJ(".btl-content-block").click(function(){requireRemove =false;});	
	BTLJ(".btl-panel span").click(function(){requireRemove =false;});	
	
	// Modify iframe
	BTLJ('#btl-iframe').load(function (){
		//edit action form	
		oldAction=BTLJ('#btl-iframe').contents().find('form').attr("action");
		if(oldAction!=null){
			if(oldAction.search("tmpl=component")==-1){
				if(BTLJ('#btl-iframe').contents().find('form').attr("action").indexOf('?')!=-1){	
					BTLJ('#btl-iframe').contents().find('form').attr("action",oldAction+"&tmpl=component");
				}
				else{
					BTLJ('#btl-iframe').contents().find('form').attr("action",oldAction+"?tmpl=component");
				}
			}
		}
	});	

});


// SHOW LOGIN FORM
function showLoginForm(){
	BTLJ('.btl-panel span').removeClass("active");
	var el = '#btl-panel-login';
	BTLJ.modal.close();
	var containerWidth = 0;
	var containerHeight = 0;
	containerHeight = 371;
	containerWidth = 357;
	
	if(BTLJ(el).hasClass("btl-modal")){
		BTLJ(el).addClass("active");
		BTLJ("#btl-content > div").slideUp();
		BTLJ("#btl-content-login").modal({
			overlayClose:true,
			// persist :true,
			autoPosition:autoPos,
			onOpen: function (dialog) {
				if(!autoPos){
					dialog.container.css(mobilePopupPos);
				}
				dialog.overlay.fadeIn();
				dialog.container.show();
				dialog.data.show();		
			},
			onClose: function (dialog) {
				dialog.overlay.fadeOut(function () {
					dialog.container.hide();
					dialog.data.hide();		
					BTLJ.modal.close();
					BTLJ('.btl-panel span').removeClass("active");
				});
			},
			containerCss:{
				height:containerHeight,
				width:containerWidth
			}
		})			 
	}
	else
	{	
		BTLJ("#btl-content > div").each(function(){
			if(this.id=="btl-content-login")
			{
				if(BTLJ(this).is(":hidden")){
					BTLJ(el).addClass("active");
					BTLJ(this).slideDown();
					}
				else{
					BTLJ(this).slideUp();
					BTLJ(el).removeClass("active");
				}						
					
			}
			else{
				if(BTLJ(this).is(":visible")){						
					BTLJ(this).slideUp();
					BTLJ('#btl-panel-registration').removeClass("active");
				}
			}
			
		})
	}
}

// SHOW REGISTRATION FORM
function showRegistrationForm(){
	BTLJ('.btl-panel span').removeClass("active");
	BTLJ.modal.close();
	var el = '#btl-panel-registration';
	var containerWidth = 0;
	var containerHeight = 0;
	containerHeight = "auto";
	containerWidth = "auto";

	if(BTLJ(el).hasClass("btl-modal")){
		BTLJ(el).addClass("active");
		BTLJ("#btl-content > div").slideUp();
		BTLJ("#btl-content-registration").modal({
			overlayClose:true,
			//persist :true,
			autoPosition:autoPos,
			onOpen: function (dialog) {
				if(!autoPos){
					dialog.container.css(mobilePopupPos);
				}
				dialog.overlay.fadeIn();
				dialog.container.show();
				dialog.data.show();		
			},
			onClose: function (dialog) {
				dialog.overlay.fadeOut(function () {
					dialog.container.hide();
					dialog.data.hide();		
					BTLJ.modal.close();
					BTLJ('.btl-panel span').removeClass("active");
				});
			},
			containerCss:{
				height:containerHeight,
				width:containerWidth
			}
		})
	}
	else
	{	
		BTLJ("#btl-content > div").each(function(){
			if(this.id=="btl-content-registration")
			{
				if(BTLJ(this).is(":hidden")){
					BTLJ(el).addClass("active");
					BTLJ(this).slideDown();
					}
				else{
					BTLJ(this).slideUp();								
					BTLJ(el).removeClass("active");
					}
			}
			else{
				if(BTLJ(this).is(":visible")){						
					BTLJ(this).slideUp();
					BTLJ('#btl-panel-login').removeClass("active");
				}
			}
			
		})
	}
}

// SHOW PROFILE (LOGGED MODULES)
function showProfile(){
	var el = '#btl-panel-profile';
	BTLJ("#btl-content > div").each(function(){
		if(this.id=="btl-content-profile")
		{
			if(BTLJ(this).is(":hidden")){
				BTLJ(el).addClass("active");
				BTLJ(this).slideDown();
				}
			else{
				BTLJ(this).slideUp();	
				BTLJ('.btl-panel span').removeClass("active");
			}				
		}
		else{
			if(BTLJ(this).is(":visible")){						
				BTLJ(this).slideUp();
				BTLJ('.btl-panel span').removeClass("active");	
			}
		}
		
	})
}

// AJAX REGISTRATION
function registerAjax(){
	BTLJ("#btl-registration-error").hide();
	 BTLJ(".btl-error-detail").hide();
	if(BTLJ("#btl-input-name").val()==""){
		BTLJ("#btl-registration-error").html(btlOpt.REQUIRED_FILL_ALL);
		BTLJ("#btl-registration-error").show();
		BTLJ("#btl-registration-name-error").html(btlOpt.REQUIRED_NAME);
		BTLJ("#btl-registration-name-error").show();
		return false;
	}
	if(BTLJ("#btl-input-username1").val()==""){
		BTLJ("#btl-registration-error").html(btlOpt.REQUIRED_FILL_ALL);
		BTLJ("#btl-registration-username-error").html(btlOpt.REQUIRED_USERNAME);		
		BTLJ("#btl-registration-error").show();
		BTLJ("#btl-registration-username-error").show();
		return false;
	}
	if(BTLJ("#btl-input-password1").val()==""){
		BTLJ("#btl-registration-error").html(btlOpt.REQUIRED_FILL_ALL);
		BTLJ("#btl-registration-pass1-error").html(btlOpt.REQUIRED_PASSWORD);
		BTLJ("#btl-registration-error").show();
		BTLJ("#btl-registration-pass1-error").show();
		return false;
	}
	if(BTLJ("#btl-input-password2").val()==""){
		BTLJ("#btl-registration-error").html(btlOpt.REQUIRED_FILL_ALL);
		BTLJ("#btl-registration-pass2-error").html(btlOpt.REQUIRED_VERIFY_PASSWORD);
		BTLJ("#btl-registration-error").show();
		BTLJ("#btl-registration-pass2-error").show();
		return false;
	}
	if(BTLJ("#btl-input-password2").val()!=BTLJ("#btl-input-password1").val()){
		BTLJ("#btl-registration-error").html(btlOpt.PASSWORD_NOT_MATCH);
		BTLJ("#btl-registration-error").show();
		return false;
	}
	if(BTLJ("#btl-input-email1").val()==""){
		BTLJ("#btl-registration-error").html(btlOpt.REQUIRED_FILL_ALL);
		BTLJ("#btl-registration-email1-error").html(btlOpt.REQUIRED_EMAIL);
		BTLJ("#btl-registration-error").show();
		BTLJ("#btl-registration-email1-error").show();
		return false;
	}
	var emailRegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/;
	if(!emailRegExp.test(BTLJ("#btl-input-email1").val())){		
		BTLJ("#btl-registration-error").html(btlOpt.EMAIL_INVALID);
		BTLJ("#btl-registration-error").show();
		return false;
	}
	if(BTLJ("#btl-input-email2").val()==""){
		BTLJ("#btl-registration-error").html(btlOpt.REQUIRED_FILL_ALL);
		BTLJ("#btl-registration-emai2-error").html(btlOpt.REQUIRED_VERIFY_EMAIL);
		BTLJ("#btl-registration-emai2-error").show();
		BTLJ("#btl-registration-error").show();
		return false;
	}
	if(BTLJ("#btl-input-email1").val()!=BTLJ("#btl-input-email2").val()){
		BTLJ("#btl-registration-error").html(btlOpt.EMAIL_NOT_MATCH);
		BTLJ("#btl-registration-error").show();
		return false;
	}
	 
	var token = BTLJ('.btl-buttonsubmit input:last').attr("name");
	var value_token = BTLJ('.btl-buttonsubmit input:last').val(); 
	var datasubmit= "task=register&name="+BTLJ("#btl-input-name").val()
			+"&username="+BTLJ("#btl-input-username1").val()
			+"&passwd1=" + BTLJ("#btl-input-password1").val()
			+"&passwd2=" + BTLJ("#btl-input-password2").val()
			+"&email1=" + BTLJ("#btl-input-email1").val()
			+"&email2=" + BTLJ("#btl-input-email2").val()					
			+ "&"+token+"="+value_token;
	if(btlOpt.RECAPTCHA =="recaptcha"){
		datasubmit  += "&recaptcha=yes&recaptcha_response_field="+ BTLJ("#recaptcha_response_field").val()
					+"&recaptcha_challenge_field="+BTLJ("#recaptcha_challenge_field").val();
	}
	
	BTLJ.ajax({
		   type: "POST",
		   beforeSend:function(){
			   BTLJ("#btl-register-in-process").show();			   
		   },
		   url: btlOpt.BT_AJAX,
		   data: datasubmit,
		   success: function(html){				  
			   //if html contain "Registration failed" is register fail
			  BTLJ("#btl-register-in-process").hide();	
			  if(html.indexOf('$error$')!= -1){
				  BTLJ("#btl-registration-error").html(html.replace('$error$',''));  
				  BTLJ("#btl-registration-error").show();
				  Recaptcha.reload();
				  
			   }else{				   
				   BTLJ(".btl-formregistration").children("div").hide();
				   BTLJ("#btl-success").html(html);	
				   BTLJ("#btl-success").show();	
				   setTimeout(function() {window.location.reload();},5000);

			   }
		   },
		   error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus + ': Ajax request failed');
		   }
		});
		return false;
}

// AJAX LOGIN
function loginAjax(){
	if(BTLJ("#btl-input-username").val()=="") {
		showLoginError(btlOpt.REQUIRED_USERNAME);
		return false;
	}
	if(BTLJ("#btl-input-password").val()==""){
		showLoginError(btlOpt.REQUIRED_PASSWORD);
		return false;
	}
	var token = BTLJ('.btl-buttonsubmit input:last').attr("name");
	var value_token = BTLJ('.btl-buttonsubmit input:last').val(); 
	var datasubmit= "task=login&username="+BTLJ("#btl-input-username").val()
	+"&passwd=" + BTLJ("#btl-input-password").val()
	+ "&"+token+"="+value_token
	+"&return="+ BTLJ("#btl-return").val();
	
	if(BTLJ("#btl-input-remember").is(":checked")){
		datasubmit += '&remember=yes';
	}
	
	BTLJ.ajax({
	   type: "POST",
	   beforeSend:function(){
		   BTLJ("#btl-login-in-process").show();
		   BTLJ("#btl-login-in-process").css('height',BTLJ('#btl-content-login').outerHeight()+'px');
		   
	   },
	   url: btlOpt.BT_AJAX,
	   data: datasubmit,
	   success: function (html, textstatus, xhrReq){
		  if(html == "1" || html == 1){
			   window.location.href=btlOpt.BT_RETURN;
		   }else{
			   if(html.indexOf('</head>')==-1){		   
				   showLoginError(btlOpt.E_LOGIN_AUTHENTICATE);
				}
				else
				{
					if(html.indexOf('btl-panel-profile')==-1){ 
						showLoginError('Another plugin has redirected the page on login, Please check your plugins system');
					}
					else
					{
						window.location.href=btlOpt.BT_RETURN;
					}
				}
		   }
	   },
	   error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus + ': Ajax request failed!');
	   }
	});
	return false;
}
function showLoginError(notice,reload){
	BTLJ("#btl-login-in-process").hide();
	BTLJ("#btl-login-error").html(notice);
	BTLJ("#btl-login-error").show();
	if(reload){
		setTimeout(function() {window.location.reload();},5000);
	}
}

