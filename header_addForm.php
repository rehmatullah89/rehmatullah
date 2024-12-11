<?php 
require_once('auth.php');
error_reporting(0);?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Sanva.com</title>
<meta name="description" content="Place your description here">
<meta name="keywords" content="put, your, keyword, here">
<meta name="author" content="Templates.com - website templates provider">
<meta charset="utf-8">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_300.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_600.font.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<!--[if lt IE 7]>
<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/html5.js"></script>
<![endif]-->
<script type="text/javascript">

 $(document).ready(function() {
/*
* In-Field Label jQuery Plugin
* http://fuelyourcoding.com/scripts/infield.html
*
* Copyright (c) 2009 Doug Neiner
* Dual licensed under the MIT and GPL licenses.
* Uses the same license as jQuery, see:
* http://docs.jquery.com/License
*
* @version 0.1
*/
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='password']," + "input#" + for_attr + "[type='tel']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


    $("#RegisterUserForm label").inFieldLabels();
});

</script>

<style type="text/css">

/* Add whatever you need to your CSS reset */

        #registration {
			color: #fff;
            background: #2d2d2d;
            background: -webkit-gradient(
                            linear,
                            left bottom,
                            left top,
                            color-stop(0, rgb(60,60,60)),
                            color-stop(0.74, rgb(43,43,43)),
                            color-stop(1, rgb(60,60,60))
                        );
            background: -moz-linear-gradient(
                            center bottom,
                            rgb(60,60,60) 0%,
                            rgb(43,43,43) 74%,
                            rgb(60,60,60) 100%
                        );
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
			border-radius: 10px;
            margin: 10px;
			width: 430px;
            }

 #registration a {
      color: #8c910b;
      text-shadow: 0px -1px 0px #000;
      }
	  
#registration fieldset {
      padding: 20px;
      }
	  
input.text {
      -webkit-border-radius: 15px;
      -moz-border-radius: 15px;
      border-radius: 15px;
      border:solid 1px #444;
      font-size: 14px;
      width: 90%;
      padding: 7px 8px 7px 30px;
      -moz-box-shadow: 0px 1px 0px #777;
      -webkit-box-shadow: 0px 1px 0px #777;
	  background: #ddd url('images/inputSprite.png') no-repeat 4px 5px;
	  background: url('images/inputSprite.png') no-repeat 4px 5px, -moz-linear-gradient(
           center bottom,
           rgb(225,225,225) 0%,
           rgb(215,215,215) 54%,
           rgb(173,173,173) 100%
           );
	  background:  url('images/inputSprite.png') no-repeat 4px 5px, -webkit-gradient(
          linear,
          left bottom,
          left top,
          color-stop(0, rgb(225,225,225)),
          color-stop(0.54, rgb(215,215,215)),
          color-stop(1, rgb(173,173,173))
          );
      color:#333;
      text-shadow:0px 1px 0px #FFF;
}	  

 input#email { 
 	background-position: 4px 5px; 
	background-position: 4px 5px, 0px 0px;
	}
	
 input#password { 
 	background-position: 4px -20px; 
	background-position: 4px -20px, 0px 0px;
	}
	
 input#name { 
 	background-position: 4px -46px; 
	background-position: 4px -46px, 0px 0px; 
	}
	
 input#tel { 
 	background-position: 4px -76px; 
	background-position: 4px -76px, 0px 0px; 
	}
	
#registration h2 {
	color: #fff;
	text-shadow: 0px -1px 0px #000;
	border-bottom: solid #181818 1px;
	-moz-box-shadow: 0px 1px 0px #3a3a3a;
	text-align: center;
	padding: 18px;
	margin: 0px;
	font-weight: normal;
	font-size: 24px;
	font-family: Lucida Grande, Helvetica, Arial, sans-serif;
	}
	
#registerNew {
	width: 203px;
	height: 40px;
	border: none;
	text-indent: -9999px;
	background: url('images/createAccountButton.png') no-repeat;
	cursor: pointer;
	float: right;
	}
	
	#registerNew:hover { background-position: 0px -41px; }
	#registerNew:active { background-position: 0px -82px; }
	
 #registration p {
      position: relative;
      }
	  
fieldset label.infield /* .infield label added by JS */ {
    color: #333;
    text-shadow: 0px 1px 0px #fff;
    position: absolute;
    text-align: left;
    top: 3px !important;
    left: 35px !important;
    line-height: 29px;
    }

</style>
</head>
<body id="page6">
<div class="tail-top1">
<!-- header -->
	<header>
		<div class="container">
			<div class="header-box">
				<div class="left">
					<div class="right">
						<nav>
							<ul>
									<li <?php if ($_GET['tab'] == 'home') echo 'class="current"'; ?>><a href="home.php?tab=home">Home</a></li>
							<li <?php if ($_GET['tab'] == 'distributors') echo 'class="current"'; ?>><a href="distributors.php?tab=distributors">Distributors</a></li>
							<li <?php if ($_GET['tab'] == 'products') echo 'class="current"'; ?>><a href="products.php?tab=products">Products</a></li>
							<li <?php if ($_GET['tab'] == 'reports') echo 'class="current"'; ?>><a href="reports.php?tab=reports">Reports</a></li>
							<li <?php if ($_GET['tab'] == 'orders') echo 'class="current"'; ?>><a href="orders.php?tab=orders">Orders</a></li>
							<li <?php if ($_GET['tab'] == 'emails') echo 'class="current"'; ?>><a href="emails.php?tab=emails">Emails</a></li>
								
							</ul>
						</nav>
						<h1><a href="home.php"><span>Sanva</span>.com</a></h1>
					</div>
				</div>
			</div>
			<span class="top-info">Welcome <b><?php echo $_SESSION['SESS_FIRST_NAME']." ".$_SESSION['SESS_LAST_NAME'];?></b> </span>
		
		</div>
	</header>
