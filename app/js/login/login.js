import { User } from "./user.js";
import { postLogin } from "./petitions_login.js";

const formulario = document.forms[0];
const [ username, password ] = formulario;

const btnLogin = document.getElementById("login");
const btnRegistrar = document.getElementById("registrar");
let msj = document.getElementById("message");




btnLogin.addEventListener("click", async (e)=>{
    e.preventDefault();
    
    if(username.value != "" && password.value != "")
    {
        let user = new User(username.value, password.value)
        const res = await postLogin('http://localhost/tp_laComanda/la_comanda/app/trabajador/login', user);
        if(res != null){    
            if(res['Login']){
                    msj.innerHTML = res['Login']
                }
            if(res['response'] == "OK"){ 
                let token = res['Token'];
                let tipoUsuario = res['Tipo_Usuario']
                msj.innerHTML = "Sesion iniciada con exito"
                msj.style.color="#45a049";
                //meter spinner, delay, algo
                //window.location.replace("http://localhost/rest_aurant/app/?token=" + token);   
                
                // Create a form with a hidden field for the token
                let form = document.createElement("form");
                form.method = "POST";
                form.action = "http://localhost/rest_aurant/app/"; 
                let tokenInput = document.createElement("input");
                tokenInput.type = "hidden";
                tokenInput.name = "token";
                tokenInput.value = token;
                form.appendChild(tokenInput);

                // Submit the form to redirect with POST
                document.body.appendChild(form);
                form.submit();
            }
        }else{
            msj.innerHTML = "Ocurrio un error, vuelva a intentarlo"
        }
    }else{
        msj.innerHTML = "No pueden quedar campos vacios"
    }
});

btnRegistrar.addEventListener("click", async (e)=>{
    e.preventDefault();

});

