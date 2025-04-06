// This callback is triggered when hCaptcha is successfully completed.
// It enables the submit button to allow form submission.
function onHCaptchaSuccess() {
    document.getElementById('submitButton').disabled = false;
}

function onHCaptchaExpired() {
    // Disable the submit button after resetting the form to ensure the user completes the hCaptcha verification again.
    document.getElementById('submitButton').disabled = true;
}

// Handle form submission
document.getElementById('emailForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const hCaptchaResponse = typeof hcaptcha !== 'undefined' ? hcaptcha.getResponse() : null;

    if (!hCaptchaResponse) {
        alert('Please complete the hCaptcha verification');
        return;
    }

    formData.append('h-captcha-response', hCaptchaResponse);

    // First verify hCaptcha
    fetch('scripts/hCaptchaVerify.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // If hCaptcha is verified, send the email
                return fetch('scripts/index/send_email.php', {
                    method: 'POST',
                    body: formData
                });
            } else {
                throw new Error(`hCaptcha verification failed: ${data.error || 'Unknown error'}`);
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(result => {
            alert(result);
            hcaptcha.reset();
            document.getElementById('emailForm').reset();
            document.getElementById('submitButton').disabled = true;
        })
        .catch(error => {
            console.error('Error in form submission or hCaptcha verification:', error);
            alert('Error: ' + error.message);
            hcaptcha.reset();
        });
});

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    this.textContent = type === 'password' ? '\u{1F441}' : '\u{1F576}';
});

