function preventNonNumeric(evt) {
    var charCode = evt.which || evt.keyCode;
    if ([8, 9, 37, 38, 39, 40, 46].includes(charCode)) {
        return;
    }
    if (charCode < 48 || charCode > 57) {
        evt.preventDefault();
    }
}
