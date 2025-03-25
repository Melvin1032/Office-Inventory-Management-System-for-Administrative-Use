    function addItem() {
        const itemContainer = document.querySelector('.item-container');
        const formGroup = document.createElement('div');
        formGroup.classList.add('form-group');
        formGroup.innerHTML = `
            <label>Select Item:</label>
            <select name="item_id[]" required>
                <option value="">Choose an available item</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?= htmlspecialchars($item['id']) ?>">
                        <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Quantity:</label>
            <input type="number" name="quantity[]" min="1" required>

            <button type="button" class="remove-btn" onclick="removeItem(this)"> X </button>
        `;
        itemContainer.appendChild(formGroup);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }