/*====================
 tap to top js
=======================*/
const btn = document.querySelector(".tap-to-top-box");

btn.addEventListener("click", function () {
  scroll(0, 500);
});
if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
  btn.style.transform = "scale(1)";
} else {
  btn.style.transform = "scale(0)";
}
window.onscroll = function showHide() {
  if (
    document.body.scrollTop > 500 ||
    document.documentElement.scrollTop > 500
  ) {
    btn.style.transform = "scale(1)";
  } else {
    btn.style.transform = "scale(0)";
  }
};

function scroll(target, duration) {
  if (duration <= 0) {
    return;
  }
  let difference = target - document.documentElement.scrollTop;
  let speed = (difference / duration) * 10;
  setTimeout(function () {
    document.documentElement.scrollTop += speed;
    if (document.documentElement.scrollTop == target) {
      return;
    }
    scroll(target, duration - 10);
  }, 10);
}
