const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);

const loginSession = $('#login');
const btnLogin = $('#btn-login');

function btnLoginOnclick(){
    // btnLogin.preventDefault();
    console.log("ok");
    loginSession.classList.toggle('active');
};