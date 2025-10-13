/*=====================
 otp js
=======================*/
function getCodeBoxElement(index) {
  return document.getElementById("five" + index);
}

function onKeyUpEvent(index, event) {
  const eventCode = event.which || event.keyCode;
  if (getCodeBoxElement(index).value.length === 1) {
    if (index !== 4) {
      getCodeBoxElement(index + 1).focus();
    } else {
      getCodeBoxElement(index).blur();

      console.log("submit code");
    }
  }
  if (eventCode === 4 && index !== 1) {
    getCodeBoxElement(index - 1).focus();
  }
}

function onFocusEvent(index) {
  for (item = 1; item < index; item++) {
    const currentElement = getCodeBoxElement(item);
    if (!currentElement.value) {
      currentElement.focus();
      break;
    }
  }
}
