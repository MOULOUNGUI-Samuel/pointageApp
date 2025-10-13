/*========================
  custom swiper js start
==========================*/
var bannerSlider = new Swiper(".banner-slider", {
  slidesPerView: 1.2,
  spaceBetween: 15,
  revearseDirection: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

var thumbnailSlider = new Swiper(".product-thumbnail", {
  direction: "horizontal",
  watchSlidesProgress: true,
  slidesPerView: 4,
  spaceBetween: 10,
  freeMode: true,
  watchSlidesProgress: true,
});

var productSlider = new Swiper(".product-thumbnail-img", {
  spaceBetween: 10,
  loop: true,
  thumbs: {
    swiper: thumbnailSlider,
  },
});

// var swiper = new Swiper(".mySwiper", {
//   direction: "horizontal",
//   loop: true,
//   spaceBetween: 10,
//   slidesPerView: 4,
//   freeMode: true,
//   watchSlidesProgress: true,
// });
// var swiper2 = new Swiper(".mySwiper2", {
//   loop: true,
//   spaceBetween: 10,
//   navigation: {
//     nextEl: ".swiper-button-next",
//     prevEl: ".swiper-button-prev",
//   },
//   thumbs: {
//     swiper: swiper,
//   },
// });
