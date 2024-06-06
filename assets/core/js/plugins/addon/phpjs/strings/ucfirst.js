function ucfirst(str) {
  //   example 1: ucfirst('kevin van zonneveld');
  //   returns 1: 'Kevin van zonneveld'
  str += '';
  var f = str.charAt(0).toUpperCase();
  return f + str.substr(1);
}