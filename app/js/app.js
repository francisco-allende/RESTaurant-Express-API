import { buildTable } from "./table.js";
import {getData} from "./petitions.js";
import { initializeEvents } from "./cards.js";

let URL_mesas="http://localhost/tp_laComanda/la_comanda/app/trabajador/"
const res = await getData(URL_mesas)
console.log(res)
let arr = res['lista_trabajadores']
buildTable(arr)












