function openForm(event, formName){
    let forms = document.getElementsByClassName("form-wrapper");
    for(let i = 0; i < forms.length; i++){
        forms[i].classList.remove("active");
    }
    document.getElementById(formName).classList.add("active");

    let buttons = document.getElementsByClassName("form-link");
    for(let i = 0; i < buttons.length; i++){
        buttons[i].classList.remove("active");
    }
    event.currentTarget.classList.add("active");
}