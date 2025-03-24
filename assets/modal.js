
function openModal() {
    document.getElementById("addInventoryModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("addInventoryModal").style.display = "none";
}

function addItem() {
    let container = document.getElementById('inventory-items');
    let newRow = document.createElement('div');
    newRow.classList.add('item-row');
    newRow.innerHTML = `
        <select name="category[]" required>
            <option value="">Select Category</option>
            <option value="Office Supplies">Office Supplies</option>
            <option value="Janitorial Supplies">Janitorial Supplies</option>
            <option value="Electrical Supplies">Electrical Supplies</option>
        </select>

        <input type="text" name="item_name[]" placeholder="Item Name" required>
        <input type="number" name="quantity[]" placeholder="Quantity" required>

        <select name="unit[]" required>
            <option value="">Units</option>
            <option value="Reams">Reams</option>
            <option value="Piece/s">Piece/s</option>
        </select>

        <button type="button" class="remove-btn" onclick="removeItem(this)">X</button>
    `;
    container.appendChild(newRow);
}

function removeItem(button) {
    button.parentElement.remove();
}
