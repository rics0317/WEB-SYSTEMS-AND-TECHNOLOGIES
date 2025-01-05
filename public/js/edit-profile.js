document.addEventListener('DOMContentLoaded', function() {
    const phoneNumberInput = document.getElementById('phone_number');
    phoneNumberInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Remove non-digit characters

        // Ensure the number has at least 11 digits
        if (value.length >= 11) {
            // If it starts with '09', replace with '+63'
            if (value.startsWith('0')) {
                value = '(+63)' + value.slice(1);
            }

            // Format the number
            value = value.replace(/^(\+63|0)(\d{3})(\d{3})(\d{4})$/, function(match, p1, p2, p3, p4) {
                return `${p1 === '0' ? '+63' : p1} ${p2} ${p3} ${p4}`;
            });
        }

        this.value = value;
    });

    const locationData = {
        regions: ["Metro Manila", "Mindanao", "North Luzon", "South Luzon", "Visayas"],
        provinces: {
            "Metro Manila": ["Metro Manila"],
            "Mindanao": ["Agusan Del Norte", "Agusan Del Sur", "Basilan", "Bukidnon", "Camiguin", "Compostela Valley", "Cotabato", "Davao Del Norte", "Davao Del Sur", "Davao Oriental", "Dinagat Islands", "Lanao Del Norte", "Lanao Del Sur", "Maguindanao", "Misamis Occidental", "Misamis Oriental", "North Cotabato", "Sarangani", "South Cotabato", "Sultan Kudarat", "Sulu", "Surigao Del Norte", "Surigao Del Sur", "Tawi-Tawi", "Zamboanga Del Norte", "Zamboanga Del Sur", "Zamboanga Sibugay"],
        },
        cities: {
            "Metro Manila": ["Caloocan City", "Ermita", "Intramuros", "Las Piñas City", "Makati City", "Malabon City", "Malate", "Mandaluyong City", "Manila City", "Marikina City", "Muntinlupa City", "Navotas City", "Paco", "Pandacan", "Parañaque City", "Pasay City", "Pasig City", "Pateros", "Port Area", "Quezon City", "Quiapo", "Sampaloc", "San Juan City", "San Miguel", "San Nicolas", "Santa Ana", "Santa Cruz", "Taguig City", "Tondo", "Valenzuela City"],
            "Sarangani": ["Glan", "Kiamba", "Maasim", "Maitum", "Malapatan", "Malungon"],
        },
        barangays: {
            "Maasim": ["Amsipit","Bales","Colon","Daliao", "Kabatiol","Kablacan","Kamanga","Kanalo","Lumasal","Lumatil","Malbang","Nomoh","Pananag","Poblacion (Maasim)","Seven Hills","Tinoto"],
        }
    };

    const locationSelector1 = document.getElementById('locationSelector1');
    const selectedLocation1 = locationSelector1.querySelector('.selected-location1');
    const dropdown1 = locationSelector1.querySelector('.location-dropdown1');
    const tabs1 = dropdown1.querySelectorAll('.location-tab1');
    const optionsContainer1 = dropdown1.querySelector('.location-options1');

    let currentSelection1 = {
        region: '',
        province: '',
        city: '',
        barangay: ''
    };

    locationSelector1.addEventListener('click', () => {
        dropdown1.style.display = dropdown1.style.display === 'none' ? 'block' :'none';
    });

    tabs1.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs1.forEach(t => t.classList.remove('active1'));
            tab.classList.add('active1');
            updateOptions1(tab.dataset.tab);
        });
    });

    function updateOptions1(level) {
        let options;
        switch (level) {
            case 'region':
                options = locationData.regions;
                break;
            case 'province':
                options = locationData.provinces[currentSelection1.region] || [];
                break;
            case 'city':
                options = locationData.cities[currentSelection1.province] || [];
                break;
            case 'barangay':
                options = locationData.barangays[currentSelection1.city] || [];
                break;
        }

        optionsContainer1.innerHTML = options.map(option =>
            `<div class="location-option1" data-value="${option}">${option}</div>`
        ).join('');

        optionsContainer1.querySelectorAll('.location-option1').forEach(option => {
            option.addEventListener('click', () => selectOption1(level, option.dataset.value));
        });
    }

    function selectOption1(level, value) {
        currentSelection1[level] = value;
        document.getElementById(level).value = value;
        if (level === 'region') {
            currentSelection1.province = '';
            currentSelection1.city = '';
            currentSelection1.barangay = '';
        } else if (level === 'province') {
            currentSelection1.city = '';
            currentSelection1.barangay = '';
        } else if (level === 'city') {
            currentSelection1.barangay = '';
        }

        updateSelectedLocation1();

        const nextLevel = {
            'region': 'province',
            'province': 'city',
            'city': 'barangay',
            'barangay': null
        }[level];

        if (nextLevel) {
            tabs1.forEach(tab => {
                if (tab.dataset.tab === nextLevel) {
                    tab.click();
                }
            });
        } else {
            dropdown1.style.display = 'none';
        }
    }

    function updateSelectedLocation1() {
        const parts = Object.values(currentSelection1).filter(Boolean);
        selectedLocation1.textContent = parts.join(', ') || 'Select location';
    }

    // Initialize with regions
    updateOptions1('region');

    // Modal functionality
    const modalContainer1 = document.getElementById('modalContainer1');
    const openModalBtn1 = document.getElementById('openModalBtn1');
    const closeModalBtn1 = document.getElementById('closeModalBtn1');

    openModalBtn1.addEventListener('click', () => {
        modalContainer1.style.display = 'block';
    });

    closeModalBtn1.addEventListener('click', () => {
        modalContainer1.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modalContainer1) {
            modalContainer1.style.display = 'none';
        }
    });

    const setDefaultButtons = document.querySelectorAll('.set-default-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const addressesList = document.querySelector('.addresses-list');

    // Initialize the default address
    let defaultAddress = addressesList.querySelector('.address-item .tag.default');
    if (defaultAddress) {
        const defaultAddressItem = defaultAddress.closest('.address-item');
        const defaultSetDefaultBtn = defaultAddressItem.querySelector('.set-default-btn');
        const defaultDeleteBtn = defaultAddressItem.querySelector('.delete-btn');

        defaultSetDefaultBtn.disabled = true;
        defaultSetDefaultBtn.style.cursor = 'not-allowed';
        defaultSetDefaultBtn.style.opacity = '0.5';
        defaultDeleteBtn.style.display = 'none';

        // Move the default address to the top
        addressesList.insertBefore(defaultAddressItem, addressesList.firstChild);
    }

    setDefaultButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove default tag from all addresses
            document.querySelectorAll('.tag.default').forEach(tag => {
                tag.classList.remove('default');
                tag.textContent = '';
            });

            // Add default tag to the clicked address
            const addressItem = button.closest('.address-item');
            const addressTags = addressItem.querySelector('.address-tags');
            const defaultTag = document.createElement('span');
            defaultTag.classList.add('tag', 'default');
            defaultTag.textContent = 'Default';
            addressTags.appendChild(defaultTag);

            // Hide delete button for the default address
            const deleteButton = addressItem.querySelector('.delete-btn');
            deleteButton.style.display = 'none';

            // Show delete buttons for non-default addresses
            deleteButtons.forEach(btn => {
                if (btn !== deleteButton) {
                    btn.style.display = 'block';
                }
            });

            // Disable the "Set as default" button for the default address
            button.disabled = true;
            button.style.cursor = 'not-allowed';
            button.style.opacity = '0.5';

            // Enable the "Set as default" button for non-default addresses
            setDefaultButtons.forEach(btn => {
                if (btn !== button) {
                    btn.disabled = false;
                    btn.style.cursor = 'pointer';
                    btn.style.opacity = '1';
                }
            });

            // Move the default address to the top
            addressesList.insertBefore(addressItem, addressesList.firstChild);
        });
    });

    // Postal Code Validation
    const postalCodeInput = document.getElementById('postal_code');
    postalCodeInput.addEventListener('input', function() {
        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4);
        }
    });
});
