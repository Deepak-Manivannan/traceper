<?php

class DisplayOperator
{
	private static $realname;
	private static $userId;
	
	public static function setUsernameAndId($name, $Id){
		self::$realname = $name;
		self::$userId = $Id;
	}
	
	private static function getMetaNLinkSection(){
		
		$str = <<<EOT
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="keywords"  content="" />
		<meta name="description" content="open source GPS tracking system" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="shortcut icon" href="images/icon.png" type="image/x-icon"/>
EOT;
		
		return $str;		
	}
	
	public static function getActivateAccountPage($callbackURL, $language, $key, $email){
	    $head = self::getMetaNLinkSection();
		$str = <<<EOT
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html>
			<head>
				<title></title>
				$head	
				<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
				<script type="text/javascript" src="js/TrackerOperator.js"></script>
				
				<script type="text/javascript" src="js/LanguageOperator.js"></script>
				<script>	
				var langOp = new LanguageOperator();
				langOp.load("$language"); 	
				$(document).ready( function(){
					var trackerOp = new TrackerOperator("$callbackURL");	
					trackerOp.langOperator = langOp;
					trackerOp.activateAccount("$key", "$email");			
				});						
				</script>
			</head>
			<body>
				<div id='loginLogo' ></div>
				<div id="activateAccountInfo">									
				</div>
				
			</body>
			</html>				
EOT;
		
		return $str;		
		
	
	}
	
	public static function getLoginPage($page, $callbackURL, $language, $pluginScript){
		$head = self::getMetaNLinkSection();
		
//		$facebookPluginLoginExt = ROOT_DIRECTORY."/plugins/FacebookConnect/login.php";
//		if (file_exists($facebookPluginLoginExt)) {
//			$extension = require($facebookPluginLoginExt);
//		}
		$str = <<<EOT
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml"
      			  xmlns:fb="http://www.facebook.com/2008/fbml">
			<head>
				<title></title>
				$head	
 			    <link rel="stylesheet" type="text/css" href="js/jquery/plugins/mb.containerPlus/css/mbContainer.css" title="style"  media="screen"/>
 
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
				<script type="text/javascript" src="js/jquery/plugins/jquery.cookie.js"></script>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    			<script type="text/javascript" src="js/jquery/plugins/mb.containerPlus/inc/jquery.metadata.js"></script> 
  				<script type="text/javascript" src="js/jquery/plugins/mb.containerPlus/inc/mbContainer.js"></script> 
	
				
				<script type="text/javascript" src="js/TrackerOperator.js"></script>
				
				<script type="text/javascript" src="js/LanguageOperator.js"></script>
				<script>	
				var langOp = new LanguageOperator();
				langOp.load("$language"); 	
				$(document).ready( function(){				    
					var trackerOp = new TrackerOperator("$callbackURL");	
					trackerOp.langOperator = langOp;
					$('#usernameLabel').text(langOp.emailLabel+":");	
					$('#passwordLabel').text(langOp.passwordLabel+":");
					$('#rememberMeLabel').text(langOp.rememberMeLabel).click(function(){
						$('#rememberMe').attr('checked', !($('#rememberMe').attr('checked')));
							
					});
					$('#forgotPasswordLink').text(langOp.forgotPassword);
					$('#sendNewPassword').attr('value', langOp.sendNewPassword);	
					$('#registerLink').text(langOp.registerLabel);	
					$('#emailLabel').text(langOp.emailLabel + ":");	
					$("#aboutus").append(langOp.aboutus);
					$("#submitLoginFormButton").val(langOp.submitFormButtonLabel);	
					$('#aboutusLink').text(langOp.aboutTitle);				

					$('#email').keypress(function(event){
						if (event.keyCode == '13'){
							sendNewPassword();	
						}
					});
					$('#username , #password').keypress(function(event){
						if (event.keyCode == '13'){
							authenticateUser();
						}						
					});		
					$('#submitLoginFormButton').click(function(){
						authenticateUser();
					});
					$('#forgotPasswordLink').click(function(){
						$('#forgotPasswordForm').mb_open();
						$('#forgotPasswordForm').mb_centerOnWindow(true);

						$('#sendNewPassword').click(function(){
						    TRACKER.sendNewPassword($('#email').val(),
						   		function(result){
	                                $('#forgotPasswordForm input[type!=button]').attr('value', '');
									$('#forgotPasswordForm').mb_close();
								});
						});
					});
					$('#registerLink').click(function(){
						$('#registerForm').mb_open();
						$('#registerForm').mb_centerOnWindow(true);
						
						$('#registerButton').click(function(){
							TRACKER.registerUser($('#registerEmail').val(), $('#registerName').val(), $('#registerPassword').val(), $('#registerConfirmPassword').val(),null, 
								function(result){
	                                $('#registerForm input[type!=button]').attr('value', '');
									$('#registerForm').mb_close();
								});						
						});	
					});	

					$('#aboutusLink').click(function(){
						$('#aboutus').mb_open();
						$('#aboutus').mb_centerOnWindow(true);
					});	

					$(".containerPlus").buildContainers({
			        	containment:"document",
			        	elementsPath:"js/jquery/plugins/mb.containerPlus/elements/",
			        	onClose:function(o){},
			        	onIconize:function(o){},
			        	effectDuration:10,
			        	zIndexContext:"auto" 
      				});		
				
				});	
				function authenticateUser(){
					TRACKER.authenticateUser($('#emailLogin').val(), $('#password').val(), $('#rememberMe').attr('checked'), function(){ $('#password').val(""); });
				}
				</script>
			</head>
			<body>
				$pluginScript
				<div align="center" style="margin-top:60px"><img src="images/logo.png" style="display:block"/>	
				<div id="userLoginForm">	
					<div><br/>
						<font id="usernameLabel"></font><br/>
						<input type="text" name="email" id="emailLogin" /><br/>
						<font id="passwordLabel"></font><br/>
						<input type="password" name="password" id="password"/><br/>
						<font class="link" id="forgotPasswordLink"></font><br/>
						<input type="checkbox" name="rememberMe" id="rememberMe"/>
						<div style="display:inline" class="link" id="rememberMeLabel"></div><br/>
					    <input type="button" id="submitLoginFormButton" value=""/> <br/>
					    
					</div>
				</div>					
				</div>
				<br/>
				<div align="center" class="link" id="registerLink"></div> 
				<br/> 
				<div align="center" class="link" id='aboutusLink'></div>					
					
				<div id='aboutus' class="containerPlus draggable {buttons:'c', skin:'default', icon:'browser.png', width:'600', closed:'true' }">  
				<div class="logo"></div></div>
							
				<div id='message_warning' class="containerPlus draggable {buttons:'c', skin:'default', icon:'alert.png',width:'600', closed:'true' }">
				</div>
				<div id='message_info' class="containerPlus draggable {buttons:'c', skin:'default', icon:'tick_ok.png',width:'600', closed:'true' }">
				</div>
				
				<div id="forgotPasswordForm" class="containerPlus draggable {buttons:'c', skin:'default', icon:'tick_ok.png',width:'500', closed:'true' }">
					<div id="emailLabel"></div>
					<div><input type="text" name="email" id="email" /><input type="button" id="sendNewPassword"/></div>
				</div>
				
				<div id="registerForm" class="containerPlus draggable {buttons:'c', skin:'default', icon:'tick_ok.png',width:'250', closed:'true' }">		
					<div id="registerEmailLabel">E-mail:</div><input class="registerFormText" type="text" id="registerEmail" /><br />
					<div id="registerNameLabel">Name:</div><input class="registerFormText" type="text" id="registerName" /><br />
					<div id="registerPasswordLabel">Password:</div><input class="registerFormText" type="password" id="registerPassword" /><br />
					<div id="registerConfirmPasswordLabel">Password Again:</div><input class="registerFormText" type="password" id="registerConfirmPassword" /><br />
					<input type="button" id="registerButton" value="Register" />
				</div>
				
			</body>
			</html>				
EOT;
		
		return $str;		
	}
	
	
	public static function getRegistrationPage($email, $invitationKey, $callbackURL)
	{
		$out = <<<EOT
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml"
      			  xmlns:fb="http://www.facebook.com/2008/fbml">
			<head>
				<title></title>
				  <link rel="stylesheet" type="text/css" href="js/jquery/plugins/mb.containerPlus/css/mbContainer.css" title="style"  media="screen"/>
 
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
				<script type="text/javascript" src="js/jquery/plugins/jquery.cookie.js"></script>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    			<script type="text/javascript" src="js/jquery/plugins/mb.containerPlus/inc/jquery.metadata.js"></script> 
  				<script type="text/javascript" src="js/jquery/plugins/mb.containerPlus/inc/mbContainer.js"></script> 
	
				
<!--				<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
-->				<script type="text/javascript" src="js/TrackerOperator.js"></script>
				
				<script type="text/javascript" src="js/LanguageOperator.js"></script>
				<script>	
				var langOp = new LanguageOperator();
				langOp.load("en"); 	
				$(document).ready( function(){				    
					var trackerOp = new TrackerOperator("$callbackURL");	
					trackerOp.langOperator = langOp;
					$('#registerButton').click(function(){
						TRACKER.registerUser($('#registerEmail').val(), $('#registerName').val(), $('#registerPassword').val(), $('#registerConfirmPassword').val(), "$invitationKey");
					});			
					
					$(".containerPlus").buildContainers({
			        	containment:"document",
			        	elementsPath:"js/jquery/plugins/mb.containerPlus/elements/",
			        	onClose:function(o){},
			        	onIconize:function(o){},
			        	effectDuration:10,
			        	zIndexContext:"auto" 
      				});					
				});	
				</script>
			</head>
			<body>
			<div id="registerForm">		
					<div id="registerEmailLabel">E-mail:</div><input type="text" id="registerEmail" value="$email" readonly="readonly"/><br />
					<div id="registerNameLabel">Name:</div><input type="text" id="registerName" /><br />
					<div id="registerPasswordLabel">Password:</div><input type="password" id="registerPassword" /><br />
					<div id="registerConfirmPasswordLabel">Password Again:</div><input type="password" id="registerConfirmPassword" /><br />
					<input type="button" id="registerButton" value="Register" />
				</div>
				<div id='message_warning' class="containerPlus draggable {buttons:'c', skin:'default', icon:'alert.png',width:'600', closed:'true' }">
				</div>
				<div id='message_info' class="containerPlus draggable {buttons:'c', skin:'default', icon:'tick_ok.png',width:'600', closed:'true' }">
				</div>
				
			</body>
			</html>
EOT;
		return $out;
		
	}
	
	public static function getMainPage($callbackURL, $userInfo, $fetchPhotosInInitialization, $updateUserListInterval, $queryIntervalForChangedUsers, $apiKey, $language, $pluginScript) {

		$head = self::getMetaNLinkSection();
		$realname = self::$realname;
		$userId = self::$userId;	
		$latitude = $userInfo->latitude;
		$longitude = $userInfo->longitude;
		$time = $userInfo->time;
		$deviceId = $userInfo->deviceId;	
		
		$str = <<<MAIN_PAGE
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title></title>
		  $head		
      
	<link type="text/css" href="js/jquery/plugins/superfish/css/superfish.css" rel="stylesheet" media="screen"/>
	 <link rel="stylesheet" type="text/css" href="js/jquery/plugins/mb.containerPlus/css/mbContainer.css" title="style"  media="screen"/>
  
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
	<script type="text/javascript" src="js/jquery/plugins/jquery.cookie.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/jquery/plugins/mb.containerPlus/inc/jquery.metadata.js"></script> 
  	<script type="text/javascript" src="js/jquery/plugins/mb.containerPlus/inc/mbContainer.js"></script> 
	
  	<script type="text/javascript" src="js/jquery/plugins/superfish/js/superfish.js"></script>
	<script type="text/javascript" src="js/DataOperations.js"></script>

	<script type="text/javascript" src="js/maps/MapStructs.js"></script>	
	<script type="text/javascript" src="js/maps/GMapOperator.js"></script>
	<script type="text/javascript" src="js/TrackerOperator.js"></script>
	<script type="text/javascript" src="js/LanguageOperator.js"></script>		
	<script type="text/javascript" src="js/bindings.js"></script>	

	<script type="text/javascript">		
		var langOp = new LanguageOperator();
		var fetchPhotosDefaultValue =  $fetchPhotosInInitialization;
		langOp.load("$language");
		
		var mapOperator = new MapOperator();
		
		$(document).ready( function(){
							
			var checked = false;
			// showPhotosOnMapCookieId defined in bindings.js
			if ($.cookie && $.cookie(showPhotosOnMapCookieId) != null){
				if ($.cookie(showPhotosOnMapCookieId) == "true"){
					checked = true;
				}				
			}
			else if (fetchPhotosDefaultValue == 1){
				checked = true;
			}
			$('#showPhotosOnMap').attr('checked', checked);
			
			
			try 
			{
				var mapStruct = new MapStruct();
			    var initialLoc = new mapStruct.Location({latitude:39.504041,
			    								  longitude:35.024414}); 
			    var initialLoc2 = new mapStruct.Location({latitude:40.504041,
			    								  longitude:36.024414});	
			    var initialLoc3 = new mapStruct.Location({latitude:40.504041,
			    								  longitude:37.024414});
			    var initialLoc4 = new mapStruct.Location({latitude:39.504041,
			    								  longitude:35.024414}); 
			    
				mapOperator.initialize(initialLoc);
				
				var contentString = '<div id="content">'+
			        '<div id="siteNotice">'+
			        '</div>'+
			        '<h1 id="firstHeading" class="firstHeading">Uluru</h1>'+
			        '<div id="bodyContent">'+
			        '<p><b>Uluru</b>, also referred to as <b>Ayers Rock</b>, is a large ' +
			        'sandstone rock formation in the southern part of the '+
			        'Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) '+
			        'south west of the nearest large town, Alice Springs; 450&#160;km '+
			        '(280&#160;mi) by road. Kata Tjuta and Uluru are the two major '+
			        'features of the Uluru - Kata Tjuta National Park. Uluru is '+
			        'sacred to the Pitjantjatjara and Yankunytjatjara, the '+
			        'Aboriginal people of the area. It has many springs, waterholes, '+
			        'rock caves and ancient paintings. Uluru is listed as a World '+
			        'Heritage Site.</p>'+
			        '<p>Attribution: Uluru, <a href="http://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">'+
			        'http://en.wikipedia.org/w/index.php?title=Uluru</a> '+
			        '(last visited June 22, 2009).</p>'+
			        '</div>'+
			        '</div>';
			    var contentString2 = '<div id="content">'+
			        '<div id="siteNotice">'+
			        '</div>'+
			        '<h1 id="firstHeading" class="firstHeading">Uluru</h1>'+
			        '<div id="bodyContent">'+
			        '<p><b>Uluru</b>, also referred to as <b>Ayers Rock</b>, is a large ' +
			        'Heritage Site.</p>'+
			        '<p>Attribution: Uluru, <a href="http://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">'+
			        'http://en.wikipedia.org/w/index.php?title=Uluru</a> '+
			        '(last visited June 22, 2009).</p>'+
			        '</div>'+
			        '</div>';    
			        
				//mapOperator.putMarker(initialLoc, "images/person.png", true);
				//mapOperator.initializeInfoWindow(contentString);
				/*
				alert(1);
				var ab=mapOperator.initializeInfoWindow(contentString);
				mapOperator.openInfoWindow(ab,mapOperator.putMarker(initialLoc, "images/person.png", true));
				alert(2);
				mapOperator.setContentOfInfoWindow(ab,contentString2);
				alert(3);
				*/
				var poly=mapOperator.initializePolyline();	
				alert(4);
				//mapOperator.clickFunction(mapOperator.abc);
				
    			mapOperator.updatePolyline(poly,initialLoc);
				mapOperator.updatePolyline(poly,initialLoc2);
				mapOperator.updatePolyline(poly,initialLoc3);
				mapOperator.updatePolyline(poly,initialLoc4);
				
   				alert(5);			
   				var trackerOp = new TrackerOperator('$callbackURL', map, $fetchPhotosInInitialization, $updateUserListInterval, $queryIntervalForChangedUsers, langOp, $userId);
   							

   				
   				var personIcon = new GIcon(G_DEFAULT_ICON);
				personIcon.image = "images/person.png";
				personIcon.iconSize = new GSize(24,24);
				personIcon.shadow = null;
				markerOptions = { icon:personIcon };
   				
				var point = new GLatLng($latitude, $longitude);
   				TRACKER.users[$userId] = new TRACKER.User( {//username:username,
									   realname:'$realname',
									   latitude:$latitude,
									   longitude:$longitude,
									   time:'$time',
									   message:'',
									   deviceId:'$deviceId',
									   gmarker:new GMarker(point, markerOptions),														   
									});
				GEvent.addListener(TRACKER.users[$userId].gmarker, "click", function() {
  					TRACKER.openMarkerInfoWindow($userId);	
  				});
  			
				GEvent.addListener(TRACKER.users[$userId].gmarker,"infowindowopen",function(){
					TRACKER.users[$userId].infoWindowIsOpened = true;
  				});
				
  				GEvent.addListener(TRACKER.users[$userId].gmarker,"infowindowclose",function(){
  					TRACKER.users[$userId].infoWindowIsOpened = false;
  				});
  				if (typeof TRACKER.users[$userId].pastPointsGMarker == "undefined") {
  					TRACKER.users[$userId].pastPointsGMarker = new Array(TRACKER.users[$userId].gmarker);
  				}
				map.addOverlay(TRACKER.users[$userId].gmarker);
   				trackerOp.getFriendList(1); 	
   				
			}
   			catch (e) {
				
			}    			
			    $(".containerPlus").buildContainers({
			        containment:"document",
			        elementsPath:"js/jquery/plugins/mb.containerPlus/elements/",
			        onClose:function(o){},
			        onIconize:function(o){},
			        effectDuration:10,
			        zIndexContext:"auto" 
      			});
      			setLanguage(langOp);
      			bindElements(langOp, trackerOp);			    
      			$('#user_title').click();
		});	
	</script>
   
	</head>
	<body>	
	$pluginScript
	<div id='wrap'>
				<div class='logo_inFullMap'></div>										
				<div id='bar'></div>
				<div id='sideBar'>						
					<div id='content'>						
	 						<div id='logo'></div>
	 						<ul id='userarea'><li id="username">$realname
	 											<!-- asagidaki iki satir dil dosyasından alınmalı -->
	 											<!--<input type="text" style='width:230px;height:25px' id="statusMessage" value="Status message"/>-->
	 											<!--<input type="button" value="Send" id="sendStatusMessageButton"/>-->
	 										   <!--
	 											<ul>
	 										   <li id="changePassword"></li>
	 										   <li id="signout"></li>
	 										   <li id="inviteUserDiv">Invite User</li>
	 										
	 											</ul>
	 										-->	
	 										</li>
	 						</ul>
	 						<div style="clear:both" id="changePassword" class="userOperations">	
	 							<img src='images/changePassword.png'  /><div></div>
	 						</div>
	 						<div class="userOperations" id="inviteUser">
	 							<img src='images/invite.png'  /><div></div>
	 						</div>
	 						<div class="userOperations" id="friendRequests">	
	 							<img src='images/friends.png'  /><div></div>
	 						</div>
	 						<div  class="userOperations" id="signout">	 			
	 							<img src='images/signout.png'  /><div></div>		
	 						</div>
	 						<div id='lists'>	
								<div class='titles'>									
									<div class='title active_title' id='user_title'><div class='arrowImage'></div></div>
									<div class='title' id='photo_title'><div class='arrowImage'></div></div>	
									<!-- <div class='title' id='friendRequest_title'><div class='arrowImage'>Friend Requests</div></div>-->							
								</div>
								<div id='friendsList'>											
									<div class='search'>						
										<input type='text' id='searchBox' value='' /><img src='images/search.png' id='searchButton'  />
									</div>
									<div id="friends"></div>
									<div class='searchResults'>
										<a href='#returnToUserList'></a>	
										<div id='results'></div>								
									</div>		
								</div>
								<div id="photosList">									
									<div class='search'>
										<input type='text' id='searchBox' value='' /><img src='images/search.png' id='searchButton'  />
									</div>
									<input type='checkbox' id='showPhotosOnMap'> Show photos on map
									<div id="photos"></div>
									<div class='searchResults'>
										<a href='#returnToPhotoList' id="returnToPhotoList"></a>	
										<div id='results'></div>								
									</div>
								</div>	
								
							<!-- <div id='footer'>							
									<a href='#auLink'></a>								
								</div>
							-->					
							</div>							
					</div>
																																									
				</div>
				
				<div id="map"></div>					
				<div id='infoBottomBar'></div>
				<div id='loading'></div>											
	</div>
  	
	<div id='aboutus' class="containerPlus draggable {buttons:'c',icon:'browser.png', skin:'default', width:'600', closed:'true'}">  
	<div class="logo"></div></div>
	<div id='changePasswordForm' class="containerPlus draggable {buttons:'c', icon:'changePass.png' ,skin:'default', width:'250', height:'225', title:'<div id=\'changePasswordFormTitle\'></div>', closed:'true' }">  
		<br/>
		<div id="currentPasswordLabel"></div>
		<div><input type='password' name='currentPassword' id='currentPassword' /></div>
		<div id="newPasswordLabel"></div>
		<div><input type='password' name='newPassword' id='newPassword' /></div>  
		<div id="newPasswordAgainLabel"></div>
		<div><input type='password' name='newPasswordAgain' id='newPasswordAgain' /></div>
		<div></div>
		<div><input type='button' name='changePassword' id='changePasswordButton' /> &nbsp; <input type='button' name='cancel' id='changePasswordCancel'/></div>
	</div>
	
	<div id='friendRequestsList' class="containerPlus draggable {buttons:'c', icon:'friends.png' ,skin:'default', width:'400', height:'550', title:'<div id=\'friendRequestsListTitle\'></div>', closed:'true' }">  
	</div>
	
	<div id='InviteUserForm' class="containerPlus draggable {buttons:'c', skin:'default', width:'350', height:'350', title:'<div id=\'inviteUserFormTitle\'></div>',  closed:'true'}">  
		<div id="inviteUserEmailLabel"></div> 
		<textarea name='useremail' id='useremail' style="width:300px; height:100px"></textarea><br/>		
		<div id="inviteUserInvitationMessage"></div>
		
		<textarea name='invitationMessage' id='invitationMessage' style="width:300px; height:100px"></textarea><br/>
		
		<input type='button' name='inviteUserButton' id='inviteUserButton'/>&nbsp; <input type='button' name='cancel' id='inviteUserCancel'/></div>
	</div>	
	
	<div id='message_warning' class="containerPlus draggable {buttons:'c', skin:'default', icon:'alert.png',width:'400', closed:'true' }">
	</div>
	<div id='message_info' class="containerPlus draggable {buttons:'c', skin:'default', icon:'tick_ok.png',width:'400', closed:'true' }">
	</div>				
	</body>
</html>
MAIN_PAGE;

		  return $str;
}	
	public static function showErrorMessage($message) {
		return $message;
	}

}