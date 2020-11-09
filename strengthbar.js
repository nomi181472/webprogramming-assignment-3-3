window.onload = () => {
  var password = document.getElementById("pass");
  var meter = document.getElementById("meter");

  password.addEventListener("input", function () {
    var val = password.value;
    //console.log(val.length);
    var count = 0;
    var isCapital = false,
      isSmall = false,
      isAlphanumeric = false,
      isDigit = false;
    if (val.length >= 8) {
      count = 20;
      for (var i = 0; i < val.length; i++) {
        if (
          val.charCodeAt(i) >= 65 &&
          val.charCodeAt(i) <= 90 &&
          isCapital !== true
        ) {
          count = count + 20;
          isCapital = true;
        } else if (
          val.charCodeAt(i) >= 97 &&
          val.charCodeAt(i) <= 122 &&
          isSmall !== true
        ) {
          count = count + 20;
          isSmall = true;
        } else if (
          val.charCodeAt(i) >= 49 &&
          val.charCodeAt(i) <= 59 &&
          isDigit !== true
        ) {
          count = count + 20;
          isDigit = true;
        } else if (
          isDigit !== true &&
          isSmall !== true &&
          isCapital !== true &&
          isAlphanumeric === false
        ) {
          count = count + 20;
          isAlphanumeric = true;
        }
        if (val.length >= 16 && count <= 200) {
          count = count + 2;
        }
      }
    }
    console.log(count);
    meter.value = count;
  });
};
