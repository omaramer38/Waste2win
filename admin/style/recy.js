document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("assignModal");
    const openButtons = document.querySelectorAll(".btn-approve-assign");
    const closeButton = document.querySelector(".close-button");
    const cancelButton = document.getElementById("cancelAssign");
    const hiddenInput = document.getElementById("recyid");

    // افتح المودال واملأ recyid
    openButtons.forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault(); // منع الريفريش
            const recyid = this.getAttribute("data-recyid");
            hiddenInput.value = recyid; // نحط الرقم في الـ input hidden
            modal.style.display = "flex";
        });
    });

    // اغلاق المودال عند الضغط على ×
    closeButton.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // اغلاق المودال عند الضغط على "إلغاء"
    cancelButton.addEventListener("click", function(e) {
        e.preventDefault();
        modal.style.display = "none";
    });

    // اغلاق المودال عند الضغط خارج الإطار
    window.addEventListener("click", function(e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});




