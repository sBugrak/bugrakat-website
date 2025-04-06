<div class="register-container">
  <h2 draggable="false" class="register-title">REGISTER</h2>
  <form class="register-form" id="emailForm" action="scripts/index/send_email.php">
    <label class="register-label" for="emailbox">Email <input type="email" name="emailbox" id="emailbox"></label>

    <label class="register-label" for="password">
      Password
      <div class="password-container">
        <input type="password" id="password" name="password" minlength="8" required />
        <span class="toggle-password" id="togglePassword">&#128065;</span>
      </div>
    </label>

    <div class="h-captcha" data-sitekey="5d3ac882-a6a2-4ae0-92da-33e039aaf5fe" data-callback="onHCaptchaSuccess"
      data-expired-callback="onHCaptchaExpired"></div>
    <button class="submit-button" type="submit" id="submitButton" disabled>Register</button>

    <p class="have-account">Already have an account? <a href="index.php?page=enter">Enter</a></p>
  </form>

  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>

  <script src="../../scripts/index/register.js"></script>
</div>