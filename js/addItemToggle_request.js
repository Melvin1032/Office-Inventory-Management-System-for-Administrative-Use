function addItem() {
    const container = document.querySelector(".item-container");
    const newItem = document.createElement("div");
    newItem.classList.add("form-group");
    newItem.innerHTML = `
        <label>Select Item:</label>
        <select name="item_id[]" required>
            <option value="">Choose an available item</option>
            ${itemsOptions()}
        </select>
        
        <label>Quantity:</label>
        <input type="number" name="quantity[]" min="1" required>
        
        <button type="button" class="remove-btn" onclick="removeItem(this)">X</button>
    `;
    container.appendChild(newItem);
}

function removeItem(button) {
    button.parentElement.remove();
}

// Function to generate available item options from PHP data
function itemsOptions() {
    return `<?php foreach ($items as $item): ?>
                <option value="<?= htmlspecialchars($item['id']) ?>">
                    <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] . ' ' . htmlspecialchars($item['unit']) ?>)
                </option>
            <?php endforeach; ?>`;
}
