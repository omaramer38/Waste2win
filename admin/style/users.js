// لما الصفحة تفتح بالكامل
document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggleBtn");
  const sidebar = document.getElementById("sidebar");
  const searchInput = document.getElementById("search");
  const usersBody = document.getElementById("usersBody");

  //  تحكم في فتح وإغلاق السايدبار
  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
  });

});