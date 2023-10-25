//<!================initialize checks=================>
if (localStorage.getItem("userId") != null) {
  window.location.replace("./home/home.html");
}

// ? ===============> Global variables<========================
let loginForm = document.querySelectorAll("form.login-form input");
let emailError = document.querySelector(".email-invalid");
let passwordError = document.querySelector(".password-invalid");
let [email, password] = getInputs(loginForm);
let loginBtn = document.querySelector("#login");
let apiResult = document.querySelector(".apiResult");
let apiError = document.getElementById("api-errors");

let userNameValidate, passwordValidate, emailValidate;
//=============> Function<========================
function getInputs(loginForm) {
  let inputArr = Array.from(loginForm);
  return inputArr;
}
function validateEmail(email) {
  emailRegx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if (emailRegx.test(email.value)) {
    console.log("valid");
    email.classList.add("is-valid");
    email.classList.remove("is-invalid");
    emailError.classList.add("d-none");
    return true;
  } else {
    console.log("invalid");
    email.classList.add("is-invalid");
    email.classList.remove("is-valid");
    emailError.classList.remove("d-none");
    return false;
  }
}
function validatePassword(password) {
  const passwordRegx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  if (passwordRegx.test(password.value)) {
    console.log("valid");
    password.classList.add("is-valid");
    password.classList.remove("is-invalid");
    passwordError.classList.add("d-none");
    return true;
  } else {
    console.log("invalid");
    password.classList.add("is-invalid");
    password.classList.remove("is-valid");
    passwordError.classList.remove("d-none");
    return false;
  }
}
async function login(formData) {
  const res = await fetch("http://localhost:8080/notes%20API/login.php", {
    method: "POST",
    body: JSON.stringify(formData),
    headers: { "Content-type": "application/json; charset=UTF-8" },
  });

  const response = await res.json();
  if (response.status == 201) {
    apiResult.classList.remove("d-none");
    apiError.innerHTML = response.message;
  } else {
    apiResult.classList.add("d-none");
    localStorage.setItem("userId", JSON.stringify(response.userId));

    window.location.replace("./home/home.html");
  }
  //   console.log(response);
}
//=============> Events<========================
email.addEventListener("input", function () {
  emailValidate = validateEmail(email);
  console.log(emailValidate);
});
password.addEventListener("input", function () {
  passwordValidate = validatePassword(password);
  console.log(passwordValidate);
});
loginBtn.addEventListener("click", function (event) {
  event.preventDefault();
  if (emailValidate && passwordValidate) {
    login({
      email: email.value,
      password: password.value,
    }).catch((error) => {
      console.log(error);
    });
  }
});
