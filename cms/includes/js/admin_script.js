//Accordian Menu
ddaccordion.init({
	headerclass: "admin_nav", //Shared CSS class name of headers group
	contentclass: "submenu", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: false, //Collapse previous content (so only one open at any time)? true/false
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", "selected"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
});


//Important
$(document).ready(function(){
	animatedcollapse.addDiv('important', 'fade=1,height=auto')
	animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
		//$: Access to jQuery
		//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
		//state: "block" or "none", depending on state
	}
	animatedcollapse.init();
});

//Row Hover
$(document).ready(function(){
	$('.row1, .row2').hover(function() {
		$(this).addClass('row3');
	}, function() {
		$(this).removeClass('row3');
	});
});

//Tree View
$(document).ready(function(){
	$("#navigation").treeview({
		collapsed: true,
		unique: true,
		persist: "location",
		animated: "normal"
	});
});

//Tabs
$(document).ready(function(){
	$(".tabs").tabs({ fx: { opacity: 'toggle' } });
});

//jCrop
$(document).ready(function(){
	/*$('#cropbox').Jcrop({
		setSelect:   [ 0, 0, 115, 75 ],
		aspectRatio: 115/75,
		boxWidth: 400,
		boxHeight: 400,
		onSelect: updateCoords
	});*/
});
function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
};
function checkCoords()
{
	if (parseInt($('#w').val())) return true;
	alert('Please select a crop region then press submit.');
	return false;
};

//Password Testing
var required = "1px solid #f00";
var normal = "1px solid #ccc";

function changeStyle(x) {
	x.style.border = normal;
	x.style.backgroundColor = '#fff';
}

function testPassword () {
	var img = document.getElementById("password_strength");
	
	var password = document.getElementById("p");
	var p = password.value;
	
	var flag1 = false;
	var flag2 = false;
	var flag3 = false;
	
	var items1 = Array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	var items2 = Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
	
	if (p.length < 4) {
		img.src="../cms/images/password1.jpg";
	} else {
		
		for (i=0; i<p.length; i++) {
			
			var char = p.charAt(i);
			
			// test for lowercase letter
			for (j=0; j<items1.length; j++) {
				if (char == items1[j]) {
					flag1 = true;
					break;
				}
			}
			
			// test for uppercase letter
			for (j=0; j<items1.length; j++) {
				if (char == items1[j].toUpperCase()) {
					flag2 = true;
					break;
				}
			}
			
			// test for numbers
			for (j=0; j<items2.length; j++) {
				if (char == items2[j]) {
					flag3 = true;
					break;
				}
			}
		}
		
		img.src="../cms/images/password1.jpg";
			
		
		if (flag1 && flag2) {
			img.src="../cms/images/password2.jpg";
		} else if (flag1 && flag3) {
			img.src="../cms/images/password2.jpg";
		} else if (flag2 && flag3) {
			img.src="../cms/images/password2.jpg";
		}
		
		if (flag1 && flag2 && flag3) {
			img.src="../cms/images/password3.jpg";
		}
	}
	
	doubleCheckPassword ();
}

function doubleCheckPassword () {
	var img = document.getElementById("password_same");
	
	var password = document.getElementById("p");
	var password2 = document.getElementById("p2");
	var p = password.value;
	var p2 = password2.value;
	
	if (p.length > 0) {
		if (p == p2) {
			img.src="../cms/images/check1.jpg";
		} else {
			img.src="../cms/images/check0.jpg";
		}
	}
}

function testLogin () {
	
	var f = document.getElementById("managelogin");
	
	var password = document.getElementById("p");
	var password2 = document.getElementById("p2");
	var p = password.value;
	var p2 = password2.value;
	
	var success = false;
	
	if (p != "") {
		if (p == p2) {
			success = true;
		}
	}
	
	if (success) {
		f.submit();	
	} else {
		password.style.border = required;
		password2.style.border = required;
	}
}

//Choose page type
function addContent(){
	document.getElementById('addContent').style.display='block';
	document.getElementById('addLink').style.display='none';
}
function addLink(){
	document.getElementById('addContent').style.display='none';
	document.getElementById('addLink').style.display='block';
}


//Date Picker
$(document).ready( function(){
	$( "#datepicker" ).datepicker({
		showOn: "both",
		buttonText: "Select Date",
		dateFormat: 'yy-mm-dd',
		dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
	});
});


//Global Settings
function getProfile(){
	$("#ga_error").hide();
	if ($("#ga_email").val()=="" || $("#ga_password").val()==""){
		$("#ga_error").html('<div style="width:600px;"><div id="alert_error"><p><b>Error!</b></p></div><div id="alert_message"><p>Valid Google Analytics email and password are required for analytics.</p></div></div>').fadeIn();
		return false;
	}else{
		var email = $("#ga_email").val();
		var password = $("#ga_password").val();
		
		var curProfile = $("#ga_profile").val();
		if (curProfile==""){
			curProfile = $("#ga_profile option:selected").val();
		}
		
		var curTracking = $("#ga_tracking").val();
		if (curTracking==""){
			curTracking = $("#ga_tracking option:selected").val();	
		}
		
		$.ajax({
			url: '../cms/includes/gapi/ajaxGAProfiles.php',
			data: 'email='+email+'&pass='+password,
			type: 'post',
			dataType: 'json',
			success: function(data){
				if (data!='fail'){
					var profileselect = '';
					profileselect = "<select name='ga_profile' id='ga_profile' class='select'>";
					for (var key in data){
						selectedText = '';
						if (curProfile==data[key]['id']){
							selectedText = 'selected';	
						}
						profileselect += "<option value='"+data[key]['id']+"' "+selectedText+">"+data[key]['name']+"</option>";
					}
					profileselect += "</select>";
					
					var trackingid = '';
					trackingid = "<select name='ga_tracking' id='ga_tracking' class='select'>";
					for(var key in data){
						selectedText = '';
						if (curTracking==data[key]['propertyId']){
							selectedText = 'selected'	
						}
						trackingid += "<option value='"+data[key]['propertyId']+"' "+selectedText+">"+data[key]['name']+"</option>";
						
					}
					trackingid += "</select>";
					
				
					$("#ga_profile").remove();
					$("#ga_tracking").remove();

					$("#ga_profile_wrapper p").prepend(profileselect);
					$("#ga_tracking_wrapper p").prepend(trackingid);
				}else{
					$("#ga_error").html('<div style="width:600px;"><div id="alert_error"><p><b>Error!</b></p></div><div id="alert_message"><p>There was an error retrieving your profiles. Please double check your username/password and ensure you have created at least one profile in your analytics account.</p></div></div>').fadeIn();
				}
			}
		});
	}
}

// Add New Option Field (Products)
function addNew(num){
	$("#add"+num).before("<tr><td style='padding-left:20px;'><p>Option Name:</p></td><td><input type='text' name='options"+num+"[]' class='input' value='' /></td></tr>");
};

//Table Sorter/Sticky Header
$(document).ready(function(){
var monthNames = {};
monthNames["January"] = "01";
monthNames["February"] = "02";
monthNames["March"] = "03";
monthNames["April"] = "04";
monthNames["May"] = "05";
monthNames["June"] = "06";
monthNames["July"] = "07";
monthNames["August"] = "08";
monthNames["September"] = "09";
monthNames["October"] = "10";
monthNames["November"] = "11";
monthNames["December"] = "12";
$.tablesorter.addParser({
  id: 'monthDayYear',
  is: function(s) {
      return false;
  },
  format: function(s) {
	  if (s==''){
		  return ''+0;
	  }else{
		
	      var date = s.match(/([^\s]+)[ ](\d{1,2}),[ ](\d{4})$/);
		  var m = monthNames[date[1]];
		  
	      var d = String(date[2]);
	      if (d.length == 1) {d = "0" + d;}
	      var y = date[3];	
	      return String(''+y +''+ m +''+ d);
	  }
  },
  type: 'numeric'
});		
		
$(".tablesorter").tablesorter({
	textExtraction:function(s){
		var $el = $(s),
		$img = $el.find('img');
		return $img.length ? $img[0].src : $el.text();
	 }
});
$(".stickyheader").stickyTableHeaders();

/*If the desired sorting on a column isn't text, add a class to the th like this: 

class = '{sorter: "monthDayYear"}';
class = '{sorter: "numeric"}';*/

});