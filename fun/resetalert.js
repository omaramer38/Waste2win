



  // كود إخفاء الـ alert بعد 4 ثواني
  setTimeout(() => {
    const alertBox = document.querySelector(".alert");
    if (alertBox) {
      alertBox.style.transition = "opacity 0.5s ease";
      alertBox.style.opacity = "0";
      setTimeout(() => alertBox.remove(), 500); // يمسحها بعد ما تختفي
    }
  }, 4000); // 4000 ملي ثانية = 4 ثواني
