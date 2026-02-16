document.addEventListener("DOMContentLoaded", function() {

    const container = document.getElementById("types-container");
    const addBtn = document.getElementById("add-type");

    let count = 1;
    const maxTypes = 4;

    // خذ الـ options من data attribute
    const optionsHTML = JSON.parse(container.dataset.options);

    addBtn.addEventListener("click", function() {

        if(count >= maxTypes){
            alert("يمكنك إضافة 4 أنواع كحد أقصى فقط");
            return;
        }

        count++;

        const block = document.createElement("div");
        block.classList.add("type-block");

        block.innerHTML = `
            <button type="button" class="remove-btn">×</button>

            <div class="form-row">
                <label>نوع النفايات *</label>
                <select name="wasteid[]" required>
                    <option value="">اختر النوع</option>
                    ${optionsHTML}
                </select>
            </div>

            <div class="form-row">
                <label>رفع صورة *</label>
                <input type="file" name="waste_image[]" accept="image/*" required>
            </div>
        `;

        container.appendChild(block);

        block.querySelector(".remove-btn").addEventListener("click", function() {
            block.remove();
            count--;
        });

    });

});
