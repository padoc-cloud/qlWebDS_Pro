function validate(chk1){
  if (chk1.checked == false) {
    alert("You have to check 'I agree to the TOS' in order to proceed.");
    return false;
  }else {
    return true;
  }
}
