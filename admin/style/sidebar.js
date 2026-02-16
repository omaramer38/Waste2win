
const زر_التبديل = document.getElementById("toggle-btn");
const الشريط_الجانبي = document.querySelector(".sidebar");

زر_التبديل.addEventListener("click", () => {
  الشريط_الجانبي.classList.toggle("collapsed");
  زر_التبديل.classList.toggle("moved");
});


