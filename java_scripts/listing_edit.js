function links53(three, five, logo, reciprocal, video) {
  var id = "links3";
  if (three) {
   hideshow(id, "");
  } else {
   hideshow(id, "none");
  }
  id = "links5";
  if (five) {
   hideshow(id, "");
  } else {
   hideshow(id, "none");
  }
  id = "logo_section";
  if (logo) {
   hideshow(id, "");
  } else {
   hideshow(id, "none");
  }
  id = "reciprocal_section";
  if (reciprocal) {
   hideshow(id, "");
  } else {
   hideshow(id, "none");
  }
  id = "video_section";
  if (video) {
   hideshow(id, "");
  } else {
   hideshow(id, "none");
  }
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
  			 document.payform.elements[id].style.display= hs;
  		 }
  	 } 
}
