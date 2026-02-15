document.addEventListener("DOMContentLoaded", () => {

  const loginTab = document.getElementById("loginTab");
  const registerTab = document.getElementById("registerTab");
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  loginTab.onclick = () => {
    loginTab.classList.add("active");
    registerTab.classList.remove("active");
    loginForm.classList.add("active");
    registerForm.classList.remove("active");
  };

  registerTab.onclick = () => {
    registerTab.classList.add("active");
    loginTab.classList.remove("active");
    registerForm.classList.add("active");
    loginForm.classList.remove("active");
  };

  const modeBtn = document.getElementById("modeBtn");
  modeBtn.onclick = () => {
    document.body.classList.toggle("dark");
    modeBtn.textContent = document.body.classList.contains("dark") 
      ? "☀️ Light Mode" 
      : "Dark Mode";
  };
});






