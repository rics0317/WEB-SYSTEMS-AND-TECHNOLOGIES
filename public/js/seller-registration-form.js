document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.user-profile')) {
        const forms = document.querySelectorAll('.registration-form');
        const progressSteps = document.querySelectorAll('.progress-step');
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        let currentStep = 1;

        // Character count for shop name input
        const shopNameInput = document.getElementById('shop_name');
        const charCount = document.querySelector('.char-count');

        shopNameInput.addEventListener('input', function() {
            charCount.textContent = `${this.value.length}/30`;
        });

        // Character count for registered name input
        const registeredNameInput = document.getElementById('registered_name');
        const registeredNameCharCount = registeredNameInput.parentElement.querySelector('.char-count');

        registeredNameInput.addEventListener('input', function() {
            registeredNameCharCount.textContent = `${this.value.length}/300`;
        });

        // Handle form submissions
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                submitForm(form);
            });
        });

        function submitForm(form) {
            const formData = new FormData(form);
            formData.append('step', currentStep); // Ensure the correct step is sent

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (currentStep < 3) {
                        showNextStep();
                    } else {
                        showSuccessMessage();
                    }
                } else {
                    showErrorMessage(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('An error occurred. Please try again.');
            });
        }

        function showNextStep() {
            if (currentStep < 3) {
                forms[currentStep - 1].style.display = 'none';
                forms[currentStep].style.display = 'block';
                updateProgressBar(currentStep + 1);
                currentStep++;
            }
        }

        function showPreviousStep() {
            if (currentStep > 1) {
                forms[currentStep - 1].style.display = 'none';
                forms[currentStep - 2].style.display = 'block';
                updateProgressBar(currentStep - 1);
                currentStep--;
            }
        }

        function updateProgressBar(step) {
            progressSteps.forEach((progressStep, index) => {
                if (index < step) {
                    progressStep.classList.add('active');
                } else {
                    progressStep.classList.remove('active');
                }
            });
        }

        function showErrorMessage(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });
        }

        function showSuccessMessage() {
            forms.forEach(form => form.style.display = 'none');
            errorMessage.style.display = 'none';
            successMessage.style.display = 'flex';
            updateProgressBar(3);

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Your registration has been submitted successfully!',
            });
        }

        // Add event listener for the "Back" buttons
        document.querySelectorAll('.btn-secondary').forEach(button => {
            button.addEventListener('click', showPreviousStep);
        });

        // File input change event listener
        const fileInput = document.getElementById('business_logo');
        const fileLabel = document.querySelector('label[for="business_logo"]');

        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file chosen';
            fileLabel.textContent = `Business Logo: ${fileName}`;
        });
    }
});

function saveFormData() {
    const shopInfoForm = document.getElementById('shop-info-form');
    const businessInfoForm = document.getElementById('business-info-form');
    const formData = new FormData();

    // Gather data from both forms
    Array.from(shopInfoForm.elements).forEach(element => {
        if (element.name) {
            formData.append(element.name, element.value);
        }
    });

    Array.from(businessInfoForm.elements).forEach(element => {
        if (element.name) {
            formData.append(element.name, element.value);
        }
    });

    let text = '';
    formData.forEach((value, key) => {
        text += `${key}: ${value}\n`;
    });

    // Prompt user to choose file format using SweetAlert
    Swal.fire({
        title: 'Choose file format',
        text: 'Select the format to save the form data',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Text',
        cancelButtonText: 'Word',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const blob = new Blob([text], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'seller_registration_form.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            const doc = `
                <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                <head><title>Seller Registration Form</title></head>
                <body>
                    <h1>Seller Registration Form</h1>
                    <pre>${text}</pre>
                </body>
                </html>
            `;
            const blob = new Blob([doc], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'seller_registration_form.docx';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    });
}