//<!================initialize checks=================>
if (localStorage.getItem("userId") != null) {
  window.location.replace("./home/home.html");
}

// ? ===============> Global variables<========================
let RegisterForm = document.querySelectorAll("form.register-form input");
let nameError = document.querySelector(".name-invalid");
let emailError = document.querySelector(".email-invalid");
let passwordError = document.querySelector(".password-invalid");
let [userName, email, password] = getInputs(RegisterForm);
let registerBtn = document.querySelector("#register");
let apiResult = document.querySelector(".apiResult");
let apiError = document.getElementById("api-errors");

let userNameValidate, passwordValidate, emailValidate;
//=============> Function<========================

function getInputs(RegisterForm) {
  let inputArr = Array.from(RegisterForm);
  return inputArr;
}

function validateName(name) {
  nameRegx = /^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/;
  if (nameRegx.test(name.value)) {
    console.log("valid");
    name.classList.add("is-valid");
    name.classList.remove("is-invalid");
    nameError.classList.add("d-none");
    return true;
  } else {
    console.log("invalid");
    name.classList.add("is-invalid");
    name.classList.remove("is-valid");
    nameError.classList.remove("d-none");

    return false;
  }
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
async function register(formData) {
  const res = await fetch("http://localhost:8080/notes%20API/index.php", {
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
    window.location.replace("./login.html");
  }
  //   console.log(response);
}

//=============> Events<========================

userName.addEventListener("input", function () {
  userNameValidate = validateName(userName);
});

email.addEventListener("input", function () {
  emailValidate = validateEmail(email);
});

password.addEventListener("input", function () {
  passwordValidate = validatePassword(password);
  console.log(passwordValidate);
});

registerBtn.addEventListener("click", function (event) {
  event.preventDefault();
  if (userNameValidate && emailValidate && passwordValidate) {
    register({
      name: userName.value,
      email: email.value,
      password: password.value,
    }).catch((error) => {
      //   console.log(error);
    });
  }
});
