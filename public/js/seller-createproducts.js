document.addEventListener('DOMContentLoaded', function() {
    const productNameInput = document.getElementById('productName');
    const charCount = document.querySelector('.char-count');
    const categorySearch = document.getElementById('categorySearch');
    const selectedCategory = document.getElementById('selected-category');
    const categorySuggestions = document.querySelector('.category-suggestions');
    const addTierButton = document.getElementById('addTier');
    const wholesaleTiers = document.querySelector('.wholesale-tiers');
    let tierCount = 1;

    productNameInput.addEventListener('input', function() {
        charCount.textContent = `${this.value.length}/100`;
    });

    categorySearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        if (searchTerm) {
            // Make an AJAX request to fetch category suggestions
            $.ajax({
                url: '/seller/api/categories/search', // Replace with your actual endpoint
                method: 'GET',
                data: { query: searchTerm },
                success: function(response) {
                    displaySuggestions(response.categories);
                },
                error: function(error) {
                    console.error('Error fetching categories:', error);
                }
            });
        } else {
            clearSuggestions();
        }
    });

    function displaySuggestions(categories) {
        clearSuggestions();
        if (categories.length > 0) {
            categories.forEach(category => {
                const li = document.createElement('li');
                li.textContent = category.name;
                li.addEventListener('click', function() {
                    selectCategory(category.id, category.name);
                });
                categorySuggestions.appendChild(li);
            });
            categorySuggestions.style.display = 'block';
        }
    }

    function clearSuggestions() {
        categorySuggestions.innerHTML = '';
        categorySuggestions.style.display = 'none';
    }

    function selectCategory(categoryId, categoryName) {
        categorySearch.value = categoryName;
        selectedCategory.textContent = categoryName;
        document.querySelector('input[name="category"]').value = categoryId;
        clearSuggestions();
    }

    // Close the suggestions dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!categorySearch.contains(event.target) && !categorySuggestions.contains(event.target)) {
            clearSuggestions();
        }
    });

    // Add new wholesale tier
    addTierButton.addEventListener('click', function() {
        const newTier = document.createElement('div');
        newTier.className = 'tier';
        newTier.innerHTML = `
            <div class="tier-label">${tierCount}. Price Tier ${tierCount}</div>
            <input type="number" placeholder="Min">
            <input type="number" placeholder="Max">
            <div class="input-container">
                <span class="currency-symbol"></span>
                <input type="number" placeholder="Unit Price">
            </div>
            <button class="remove-tier">×</button>
        `;
        wholesaleTiers.appendChild(newTier);
        tierCount++;
    });

    // Remove wholesale tier
    wholesaleTiers.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-tier')) {
            event.target.closest('.tier').remove();
            updateTierLabels();
        }
    });

    function updateTierLabels() {
        const tiers = document.querySelectorAll('.tier');
        tiers.forEach((tier, index) => {
            tier.querySelector('.tier-label').textContent = `${index + 1}. Price Tier ${index + 1}`;
        });
        tierCount = tiers.length + 1;
    }

    // Image upload preview
    document.querySelectorAll('.image-upload-box input[type="file"]').forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    event.target.parentNode.appendChild(img);
                    event.target.parentNode.querySelector('.upload-label').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // Handle form submission
    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: this.action,
            method: this.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Handle success response
                window.location.href = response.redirect;
            },
            error: function(error) {
                // Handle error response
                console.error('Error:', error);
            }
        });
    });
});
