function confirmDelete(msg)
{
    if (msg===undefined) msg='Are you sure?';
    var agree=confirm(msg);
    if (agree)
        return true;
    else
        return false;
}

function ConfirmForm() {
  // document.links_form.submit();
    var msg='Are you sure?';
    var agree=confirm(msg);
    if (agree)
        return true;
    else
        return false;
}

function populate(div_id) {

  	 if (document.getElementById) { // DOM3 = IE5, NS6
  	 
    		if (document.getElementById(div_id).style.display == '') {
    		
          document.getElementById(div_id).style.display = 'none';
          var str = document.getElementById('a_'+div_id).innerHTML;
          document.getElementById('a_'+div_id).innerHTML = '+'+ str.substr(1);
          
        } else {
          document.getElementById(div_id).style.display = '';
          var str = document.getElementById('a_'+div_id).innerHTML;
          document.getElementById('a_'+div_id).innerHTML = '-'+ str.substr(1);
        }

  	 }
  	 else {
  	 	if (document.layers) { // Netscape 4
    		
        if (document.div_id.display == '') {
          document.div_id.display = 'none';
        } else {
          document.div_id.display = '';
        }
          			 
  		}
  		else { // IE 4
	
        if (document.elements[div_id].display == '') {
          document.elements[div_id].display = 'none';
        } else {
          document.elements[div_id].display = '';
        }
           		  
  		}
  	 } 
}

function setVisible(obj_id, typ, e)
{

  var xOf = 330;
  var yOf = 150;
  
	obj = document.getElementById('info_'+obj_id);
  obj_td = document.getElementById('td_'+obj_id);
	
	if (typ) {
	 obj.style.display = '';

  	if (document.documentElement.scrollLeft)
  	{

  		theLeft = document.documentElement.scrollLeft;
  		theTop = document.documentElement.scrollTop;
  	}
  	else if (document.body)
  	{
  		theLeft = document.body.scrollLeft;
  		theTop = document.body.scrollTop;
  	}	
    
  	theLeft = e.screenX;
  	theTop = e.screenY;
  	   
  	theLeft = obj_td.offsetLeft;
  	theTop = obj_td.offsetTop;

  	obj.style.left = theLeft + xOf + 'px' ;
  	obj.style.top = theTop + yOf + 'px' ;
	  //setTimeout("setVisible()", 10, obj_id, false, e);
  } else {
	 obj.style.display = 'none';
  
  }
  return false;

}

function select_all(name, value) {
  
  var forminputs = document.getElementsByTagName('input');
  
  if ( document.links_form.check.value == "Check All" ) {
    document.links_form.check.value= "Uncheck All";
    value = 1;
  }
  else {  
    document.links_form.check.value= "Check All";
    value = 0;  
  }  
  
  for (i = 0; i < forminputs.length; i++) {
    
    var regex = new RegExp(name, "i");
    
    if (regex.test(forminputs[i].getAttribute('name'))) {
      
      if (value == '1') {
        forminputs[i].checked = true;
      } else {
        forminputs[i].checked = false;
      }
      
    }
    
  }
  
}

function parseAmount(name){
	var s = name.value;
	var out = '';

	if (s == "")
		return (true);

    for (i = 0; i < s.length; i++)
      {

         var cl = s.charAt(i);
         var c = s.charCodeAt(i);
         if (c == 44 ) {
            out = out + '.';
         } else if ( ((c > 47) && (c < 59)) || (c == 46)){
             out = out + cl;
         } else {

         }

     }
    name.value = out;
}

function SetClass(id, clName) {

	 document.getElementById('tab' + id).className = clName;
}

function tabAdmin(active) {

   hideshow(hide1, "none");
   hideshow(show1, "block");
	 document.getElementById('tab' + hide1).className = '';	
	 document.getElementById('tab' + show1).className = 'active';   
}

function hideshow(id, hs) {

  	 if (document.getElementById) { // DOM3 = IE5, NS6
    		document.getElementById(id).style.display = hs;

  	 }
  	 else {
  	 	 if (document.layers) { // Netscape 4
  			 document.id.display = hs;
  		 }
  		 else { // IE 4
  			 document.elements[id].display= hs;
  		 }
  	 } 
}

function visit_count(site_id,home_url) {
 
    img = new Image();
    img.src= home_url + 'visit_count.php?site_id=' + site_id;
    return true;
  
//      var agree=confirm('URL: '+home_url+'visit_count.php?site_id='+site_id);
//    if (agree)
//        return true;
//    else
//        return false;
  
}
