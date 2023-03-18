import { buildTable } from "./table.js";
import {getData} from "./petitions.js";
import { initializeEvents } from "./cards.js";

let URL_mesas="http://localhost/tp_laComanda/la_comanda/app/mesas/"
const res = await getData(URL_mesas)
console.log(res)
let arr = res['lista_Mesas']
buildTable(arr)










