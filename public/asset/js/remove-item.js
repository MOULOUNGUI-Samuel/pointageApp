/*======================
 Remove Item js
=======================*/
const cartBox = document.querySelectorAll(".vertical-product");
cartBox?.forEach((el) => {
  const deleteButton = el.querySelector(".close");
  deleteButton.addEventListener("click", function () {
    this.closest(".col-12").style.display = "none";
  });
});

const addressBox = document.querySelectorAll(".address-box");
addressBox?.forEach((el) => {
  const deleteButton = el.querySelector(".remove");
  deleteButton.addEventListener("click", function () {
    this.closest(".address-box").style.display = "none";
  });
});
