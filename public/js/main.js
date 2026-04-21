//funcion cambio en select ALINEACION PED de ADD_PROGRAM.HTML

function confirmDelete() {
      if (confirm('¿Estás Seguro de ELIMINAR este PROGRAMA? Los ENTREGABLES de este programa también serán eliminados.')) {
          return true;
      } else {
          return false;
      }
  }

function confirmDeleteEntregable(){
      if (confirm('¿Estás Seguro de ELIMINAR este ENTREGABLE? EL CONTENIDO DE AVANCES E INFORME de este entregable también serán eliminados.')) {
            return true;
        } else {
            return false;
        }
}

function confirmDeleteAvance(){
      if (confirm('¿Estás Seguro de ELIMINAR este AVANCE?')) {
            return true;
        } else {
            return false;
        }
      }

function confirmDeleteIndicador(){
            if (confirm('¿Estás Seguro de ELIMINAR este INDICADOR?')) {
                  return true;
              } else {
                  return false;
              }
            }

      let formChanged = false;
     
window.onbeforeunload = function(){
      myForm = document.getElementById('btn-informe');

      myForm.onclick = function(){
       formChanged = true;
}
if (formChanged == false) {
      if(document.getElementById('informepage')){    
            return 'No a Guardado los Cambios ¿Desea Guardarlos?';
  }
}
}

window.onload = function(){
var pwd1 = document.getElementById('pswd1');
var pwd2 = document.getElementById('pswd2');
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var largo = document.getElementById("largo");

pwd1.onfocus = function() {
      document.getElementById("message-red").style.display = "block";
    }

    pwd1.onblur = function() {
      document.getElementById("message-red").style.display = "none";
    }

    pwd1.onkeyup = function() {
      // Validate lowercase letters
      var lowerCaseLetters = /[a-z]/g;
      if(pwd1.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }
    
      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if(pwd1.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
      }
    
      // Validate numbers
      var numbers = /[0-9]/g;
      if(pwd1.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
      }
    
      // Validate length
      if(pwd1.value.length >= 8) {
        largo.classList.remove("invalid");
        largo.classList.add("valid");
      } else {
        largo.classList.remove("valid");
        largo.classList.add("invalid");
      }
    }


    pwd2.onfocus = function() {
      document.getElementById("message-red").style.display = "block";
    }

    pwd2.onblur = function() {
      document.getElementById("message-red").style.display = "none";
    }

    pwd2.onkeyup = function() {
      // Validate lowercase letters
      var lowerCaseLetters = /[a-z]/g;
      if(pwd2.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }
    
      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if(pwd2.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
      }
    
      // Validate numbers
      var numbers = /[0-9]/g;
      if(pwd2.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
      }
    
      // Validate length
      if(pwd2.value.length >= 8) {
        largo.classList.remove("invalid");
        largo.classList.add("valid");
      } else {
        largo.classList.remove("valid");
        largo.classList.add("invalid");
      }
    }
}

$(document).ready(function() {
      $('#programa-table').DataTable( {
            searching: false,
            "language": {
                  "lengthMenu": "Mostrar _MENU_ registros por página",
                  "info": "Mostrando página _PAGE_ de _PAGES_",
                  "paginate": {
                        "next": "Siguiente",
                        "last": "Última página",
                        "previous": "Anterior"
                      }
            }
      } );

      $('#table-entregable').DataTable( {
            searching: false,
            "language": {
                  "lengthMenu": "Mostrar _MENU_ registros por página",
                  "info": "Mostrando página _PAGE_ de _PAGES_",
                  "paginate": {
                        "next": "Siguiente",
                        "last": "Última página",
                        "previous": "Anterior"
                      }
            }
      } ); 

      $('#table-mapa').DataTable( {
            searching: false,
            "language": {
                  "lengthMenu": "Mostrar _MENU_ registros por página",
                  "info": "Mostrando página _PAGE_ de _PAGES_",
                  "paginate": {
                        "next": "Siguiente",
                        "last": "Última página",
                        "previous": "Anterior"
                      }
            }
      } ); 
  } );
//funcion para avance-datos-generales.html
function buscarNumero(){
      console.log("hola soy checkValue de input en avance-datos-generales" );
      mt1 = parseInt(document.getElementById('m_t1').value);
      md1 = parseInt(document.getElementById('m_d1').value);
      mi1 = parseInt(document.getElementById('m_i1').value);

      mts= parseInt(document.getElementById('m_ts').value);
      mds = parseInt(document.getElementById('m_ds').value);
      mis =parseInt(document.getElementById('m_is').value);

      ht1 = parseInt(document.getElementById('h_t1').value);
      hd1 = parseInt(document.getElementById('h_d1').value);
      hi1 = parseInt(document.getElementById('h_i1').value);

      hts= parseInt(document.getElementById('h_ts').value);
      hds = parseInt(document.getElementById('h_ds').value);
      his = parseInt(document.getElementById('h_is').value);

      

      if(md1 > mt1 || mi1 > mt1 || mds > mts || mis > mts || hd1 > ht1 || hi1 > ht1 || hds > hts || his > hts){
            document.getElementById('advertencia').style.display='block'
             
      }else if(md1 + mi1 > mt1 || mds + mis > mts || hd1 + hi1 > ht1 || hds + his > hts ){
            document.getElementById('advertencia').style.display='block'

      }else{
            document.getElementById('advertencia').style.display='none' 
      
      }
      

}

function buscarNumeroExterno(){
      mt = parseInt(document.getElementById('m_t').value);
      md = parseInt(document.getElementById('m_d').value);
      mi = parseInt(document.getElementById('m_i').value);

      ht = parseInt(document.getElementById('h_t').value);
      hd = parseInt(document.getElementById('h_d').value);
      hi = parseInt(document.getElementById('h_i').value);

      if( md >mt || mi>mt || hd>ht || hi>ht){
            document.getElementById('advertencia').style.display='block'
             
      }else if( md + mi > mt || hd + hi > ht){
            document.getElementById('advertencia').style.display='block'

      }else{
            document.getElementById('advertencia').style.display='none' 
      
      }
}




//funcion para informe.html
function mostrarTexto(){
      var trimestre = document.getElementsByName("trimestre");
    var periodo =  document.getElementById('periodo').value;
    var accion =  document.getElementById('accion').value;
     var persona = document.getElementById('personas').value;
     var municipio =  document.getElementById('municipios').value;
     var objetivo =  document.getElementById('objetivo').value;
     var descripcion = document.getElementById('descripcion').value;

     for(i = 0; i < trimestre.length; i++) {
      if(trimestre[i].checked && trimestre[i].value == "trimestre1"){
            document.getElementById('trimestre1').className="collapse show";
            return document.getElementById("resumen1").innerHTML = periodo + "," +accion + "," + persona + "," + municipio + "," + objetivo + "." + descripcion;
      }else if(trimestre[i].checked && trimestre[i].value == "trimestre2"){
            document.getElementById('trimestre2').className="collapse show";
            return document.getElementById("resumen2").innerHTML = periodo + "," +accion + "," + persona + "," + municipio + "," + objetivo + "." + descripcion;
      }else if(trimestre[i].checked && trimestre[i].value == "trimestre3"){
            document.getElementById('trimestre3').className="collapse show";
            return document.getElementById("resumen3").innerHTML = periodo + "," +accion + "," + persona + "," + municipio + "," + objetivo + "." + descripcion;
      }else if(trimestre[i].checked && trimestre[i].value == "trimestre4"){
            document.getElementById('trimestre4').className="collapse show";
            return document.getElementById("resumen4").innerHTML = periodo + "," +accion + "," + persona + "," + municipio + "," + objetivo + "." + descripcion;
      }         
     }

     
}

const removeAccents = (str) => {
      return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    } 

function buscador(){
      const table= document.getElementById('programa-table');
      const divi = document.getElementById('busca');
      const cadena = document.getElementById('buscadorprograma').value.toUpperCase();
      
      const search = removeAccents(cadena);
      let total =0;

      //recorrer las filas con contenido de la tabla

      for (let i=1; i<table.rows.length; i++){
            //si el td tiene la clase no search no se busca el contenido 
            if(table.rows[i].classList.contains("noSearch")){
                  continue;
            }

            let found = false;
            const cellsOfRow = table.rows[i].getElementsByTagName('td');
            //recorriendo celdas
            for(let j=0; j<cellsOfRow.length && !found; j++){
                  const compare = cellsOfRow[j].innerHTML.toUpperCase();
                  const compareWith = removeAccents(compare)
                 
                  //Buscando texto en el contenido de la tabla
                  if(search.length==0|| compareWith.indexOf(search)>-1 ){
                        found = true;
                        total ++;
                  }
            }
            if(found){
                  table.rows[i].style.display = '';
            }else{
                  //si no encuentra nada , esconda la fila de la tabla

                  table.rows[i].style.display = 'none';
            }

            //mostrando las coincidencias
     /* const lastTR = table.rows[table.rows.length-1];
            const td = lastTR.querySelector("td");*/
            divi.classList.remove("hide", "red");
            if(search == ""){
                divi.classList.add("hide");
            }else if (total){
                 divi.innerHTML= "Se ha encontrado " + total + " coincidencia" + ((total>1)?"s":"");

            }else{
                  divi.classList.add("red");
                  divi.innerHTML= "No se encontraron coincidencias"
            }
      }

  

}

function buscadorEntregable(){
      const table= document.getElementById('table-entregable');
      const cadena = document.getElementById('buscadorentregable').value.toUpperCase() 
      const search = removeAccents(cadena)
      const divi = document.getElementById('busca');
      let total =0;
      
      //recorrer las filas con contenido de la tabla

      for (let i=1; i<table.rows.length; i++){
            //si el td tiene la clase no search no se busca el contenido 
            if(table.rows[i].classList.contains("noSearch")){
                  continue;
            }

            let found = false;
            const cellsOfRow = table.rows[i].getElementsByTagName('td');
            //recorriendo celdas
            for(let j=0; j<cellsOfRow.length && !found; j++){
                  const compare = cellsOfRow[j].innerHTML.toUpperCase();
                  const compareWith = removeAccents(compare)
                  //Buscando texto en el contenido de la tabla
                  if(search.length==0|| compareWith.indexOf(search)>-1 ){
                        found = true;
                        total ++;
                  }
            }
            if(found){
                  table.rows[i].style.display = '';
            }else{
                  //si no encuentra nada , esconda la fila de la tabla

                  table.rows[i].style.display = 'none';
            }

            //mostrando las coincidencias
     /* const lastTR = table.rows[table.rows.length-1];
            const td = lastTR.querySelector("td");*/
            divi.classList.remove("hide", "red");
            if(search == ""){
                divi.classList.add("hide");
            }else if (total){
                 divi.innerHTML= "Se ha encontrado " + total + " coincidencia" + ((total>1)?"s":"");

            }else{
                  divi.classList.add("red");
                  divi.innerHTML= "No se encontraron coincidencias"
            }
      }



}


function cambio_eje(value){

     //json politica transferido de twig a js y parseado
     var datapolitica = document.querySelector('#select-politica').dataset.isPolitica;
     var p = JSON.parse(datapolitica);
     //obtener select correspondiente a politica
     var politica = document.getElementById('select-politica');
     console.log(value + "value de cambio_eje")

    
     if (value.length == 0) politica.innerHTML = "<option></option>";
      else{
            var politicaopt = "<option value='0'>" + "Seleccione..."+ "</option>";
            //for para leer json politica
            for(let i=0; i<p.length; i++){
              //si la condicion se cumple asignar politicas correspondientes a select
                if(p[i]["fk_eje"] == value ){
                  politicaopt += "<option value='" + p[i].id + "'>" + p[i].politica + "</option>";                              
            }           
                  }                  
            }         
            politica.innerHTML = politicaopt;

     
           }

      
function cambio_objped(){
      //json objetivoped transferido de twig a js y parseado
      var dataobjetivo = document.querySelector('#select-objped').dataset.isObjetivoped;
      var oped = JSON.parse(dataobjetivo);

      var politica = document.getElementById('select-politica');
      var valuepolitica = politica.options[politica.selectedIndex].value;

      var objetivoped= document.getElementById('select-objped');
      var valueobjetivo = objetivoped.options[objetivoped.selectedIndex].value;
                
                  if (valueobjetivo.length == 0) objetivoped.innerHTML = "<option></option>";
                  else{
                  var objetivopt = "<option value='0'>" + "Seleccione..."+ "</option>";
                for(let o=0; o<oped.length; o++){
                      if(valuepolitica == oped[o]["fk_politica"] ){
                        objetivopt+= "<option value='" + oped[o].id + "'>" + oped[o].objetivo + "</option>";    
                      }
                }
                objetivoped.innerHTML = objetivopt;
            }
}

function cambio_estrategia(){
      var dataestrategia = document.querySelector('#select-estrategiaped').dataset.isEstrategiaped;
      var estrategia = JSON.parse(dataestrategia);

      var objetivoped= document.getElementById('select-objped');
      var valueobjetivo = objetivoped.options[objetivoped.selectedIndex].value;

      var estrategiaped = document.getElementById('select-estrategiaped');
      var valueestrategia = estrategiaped.options[estrategiaped.selectedIndex].value;

      if (valueestrategia.length == 0) estrategiaped.innerHTML = "<option></option>";
                  else{
                  var estrategiaopt = "<option value='0'>" + "Seleccione..."+ "</option>";
                for(let e=0; e<estrategia.length; e++){
                      if(valueobjetivo == estrategia[e]["fk_objetivo"] ){
                        estrategiaopt+= "<option value='" + estrategia[e].id + "'>" + estrategia[e].estrategia + "</option>";    
                      }
                }
                estrategiaped.innerHTML = estrategiaopt;
            }
}

function cambio_lineaccion(){
      
      var datalinea = document.querySelector('#select-lineaccion').dataset.isLineaaccion;
      var la = JSON.parse(datalinea);

      var estrategiaped = document.getElementById('select-estrategiaped');
      var valueestrategia = estrategiaped.options[estrategiaped.selectedIndex].value;

      var lineaccionped = document.getElementById('select-lineaccion');
      var valuelineaccion = lineaccionped.options[lineaccionped.selectedIndex].value;

      if (valuelineaccion.length == 0) lineaccionped.innerHTML = "<option></option>";
      else{
      var lineaopt = "<option value='0'>" + "Seleccione..."+ "</option>";
            for(let l=0; l<la.length; l++){
             if(valueestrategia == la[l]["fk_estrategia"] ){
                  lineaopt+= "<option value='" + la[l].id + "'>" + la[l]["linea_accion"] + "</option>";    
             }
    }
    lineaccionped.innerHTML = lineaopt;
}

}


//funciones para agregar datos seleccionados en tabla de add_program.html

var selectedRow = null;

function onSubmitAgregar(){
      var data ={}
      var eje = document.getElementById('select-eje');
      var ideje= eje.options[eje.selectedIndex].value;
      var texteje = eje.options[eje.selectedIndex].text;

      var politica = document.getElementById('select-politica');
      var idp = politica.options[politica.selectedIndex].value;
      var textp = politica.options[politica.selectedIndex].text;

      var objetivoped= document.getElementById('select-objped');
      var idobjetivo = objetivoped.options[objetivoped.selectedIndex].value;
      var textobjetivo = objetivoped.options[objetivoped.selectedIndex].text;

      var estrategiaped = document.getElementById('select-estrategiaped');
      var idestrategia = estrategiaped.options[estrategiaped.selectedIndex].value;
      var textestrategia = estrategiaped.options[estrategiaped.selectedIndex].text;
     

      var lineaccionped = document.getElementById('select-lineaccion');
      var idlinea = lineaccionped.options[lineaccionped.selectedIndex].value;
      var textlinea = lineaccionped.options[lineaccionped.selectedIndex].text;

      data["eje"]=texteje;
      data["ideje"]=ideje;
      data["politica"]=textp;
      data["idp"]=idp;

      data["objetivo"]=textobjetivo;
      data["idobjetivo"]=idobjetivo;
     
      data["estrategia"]=textestrategia
      data["idestrategia"]=idestrategia
     
      data["lineaccion"]=textlinea;
      data["idlinea"]=idlinea;

     
      if (selectedRow == null){
            agregarPed(data);
           
      }else{
            updateRecord(data);
      }
  
      resetSelect();
}

function agregarPed(data){

      var table = document.getElementById("tabla-ped").getElementsByTagName('tbody')[0];
    console.log(data.ideje);
      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
      cell1.innerHTML = '<textarea id="' + data.ideje +'" name="textareaideje[]" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.eje+'</textarea>'; 
      var cell2 = row.insertCell(1);   
      cell2.innerHTML = '<textarea id="' + data.idp +'" name="textareapolitica[]" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.politica+'</textarea>'; 
      var cell3 = row.insertCell(2);
      cell3.innerHTML = '<textarea id="' + data.idobjetivo +'" name="textareaobjetivo[]" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.objetivo+'</textarea>'; 
      var cell4 = row.insertCell(3);
      cell4.innerHTML ='<textarea id="' + data.idestrategia +'" name="textareaestrategia[]" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.estrategia+'</textarea>'; 
      var cell5 = row.insertCell(4);
      cell5.innerHTML = '<textarea id="' + data.idlinea +'" name="textarealinea[]" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.lineaccion+'</textarea>'; 
      var cell6 = row.insertCell(5);   
      cell6.innerHTML = cell6.innerHTML+ '<a class="btn btn-edit-entregable" onClick="editRow(this)"><i class="fas fa-edit"></i></a> <a type="button" class="btn btn-delete-entregable" onclick="deleteRow(this)"><i class="fas fa-trash-alt"></i></a>';

}

function resetSelect(){
       document.getElementById('select-eje').value= "0";
     
     document.getElementById('select-politica').value="0";
      
     document.getElementById('select-objped').value="0";
      
      document.getElementById('select-estrategiaped').value="0";
    
     document.getElementById('select-lineaccion').value="0";

     selectedRow = null;
     
}

function editRow(td){
         
     selectedRow = td.parentElement.parentElement;
  
     document.getElementById('select-eje').value=document.getElementsByName("textareaideje")[0].id;
     
     document.getElementById('select-politica').value=document.getElementsByName("textareapolitica")[0].id;
      
     document.getElementById('select-objped').value=document.getElementsByName("textareaobjetivo")[0].id;
      document.getElementById('select-estrategiaped').value=document.getElementsByName("textareaestrategia")[0].id;
    
     document.getElementById('select-lineaccion').value=document.getElementsByName("textarealinea")[0].id;
     
      
}

function editRowPED(value){
      console.log("HOLA SOY EDIT ROW" + value);
     
}

function updateRecord(data) {
      selectedRow.cells[0].innerHTML = '<textarea id="' + data.ideje +'" name="textareaideje" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.eje+'</textarea>'; 
      selectedRow.cells[1].innerHTML = '<textarea id="' + data.idp +'" name="textareapolitica" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.politica+'</textarea>'; 
      selectedRow.cells[2].innerHTML = '<textarea id="' + data.idobjetivo +'" name="textareaobjetivo" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.objetivo+'</textarea>'; 
      selectedRow.cells[3].innerHTML = '<textarea id="' + data.idestrategia +'" name="textareaestrategia" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.estrategia+'</textarea>'; 
      selectedRow.cells[4].innerHTML =  '<textarea id="' + data.idlinea +'" name="textarealinea" style="width:130px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+data.lineaccion+'</textarea>'; 
     
  }


  function deleteRow(td) {
      if (confirm('¿Estás seguro o segura de eliminar la información ?')) {
          row = td.parentElement.parentElement;
          document.getElementById("tabla-ped").deleteRow(row.rowIndex);
          resetSelect();
      }
  }


  //FUNCIONES PARA AGREGAR INFORMACION SELECCIONADA A LA TABLA DE LA SEECION ODS y AVG ADD_ENTREGABLE.HTML y EDIT_ENTREGABLE.HTML

function agregar_ods(){
     

      var ods = document.getElementById('ods');
      var odstext = ods.options[ods.selectedIndex].text;

      var table = document.getElementById("table-ods").getElementsByTagName('tbody')[0];
 
      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
       cell1.innerHTML ='<textarea name="ods" style="width:600px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+odstext+'</textarea>'; 
      var cell6 = row.insertCell(1);   
      cell6.innerHTML = cell6.innerHTML+ '<a type="button" class="btn btn-delete-entregable" onclick="deleteOds(this)" title="ELIMINAR"><i class="fas fa-trash-alt"></i></a>';
      resetOds();
}

function deleteOds(td) {
      if (confirm('¿Estás seguro o segura de eliminar ?')) {
          row = td.parentElement.parentElement;
          document.getElementById("table-ods").deleteRow(row.rowIndex);
          resetOds();
      }
  }

  function resetOds(){
      document.getElementById('ods').value="0";

  }

  //add AVG IN ADD_ENTREGABLE Y EDIT_ENTREGABLE

  function agregar_avg(){
     

      var avg = document.getElementById('idavg');
      var avgtext = avg.options[avg.selectedIndex].text;


      var table = document.getElementById("table-avg").getElementsByTagName('tbody')[0];
     
      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
      cell1.innerHTML = '<textarea name="avg" style="width:600px;height:90px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+avgtext+'</textarea>'; 
      var cell6 = row.insertCell(1);   
      cell6.innerHTML = cell6.innerHTML+ '<a type="button" class="btn btn-delete-entregable" onclick="deleteAvg(this)" title="ELIMINAR"><i class="fas fa-trash-alt"></i></a>';
      resetAvg();
}

function deleteAvg(td) {
      if (confirm('¿Estás seguro o segura de eliminar ?')) {
          row = td.parentElement.parentElement;
          document.getElementById("table-avg").deleteRow(row.rowIndex);
          resetAvg();
      }
  }

  function resetAvg(){
      document.getElementById('idavg').value="0";
  }

  //Add fuente de financiamiento en  add_entregable.html CON rol 'ADMINISTRADOR'
  var selectedFuenteRow = null

  function onSubmitFuente(){
      var data ={}
       
      var fuente = document.getElementById('fuenteid');
      var fuentetext = fuente.options[fuente.selectedIndex].text;
      var fuentevalue = fuente.options[fuente.selectedIndex].value;
      var montovalue= document.getElementById('montoid').value;

      
      data['fuentetext']=fuentetext;
      data['fuentevalue'] = fuentevalue;
      data['montovalue']=montovalue;
      if(selectedFuenteRow == null){
            agregar_fuente(data) 
           
      }else{
            updateFuente(data);
      }
      resetFuente();
      sumar();
  }
  function agregar_fuente(data){
   
      var table = document.getElementById("fuente-financiamiento").getElementsByTagName('tbody')[0];
      var p = parseInt(data.montovalue);
      const formatter = new Intl.NumberFormat("en-US", {style: "currency", currency: "USD"});
      const n = formatter.format(p);

      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
      cell1.innerHTML = '<input id="'+ data.fuentevalue +'" name="rowfuente[]" style="width:400px;height:50px;border:0;outline:0;display:inline-block" value="'+ data.fuentetext+'" readonly>'; 
      var cell2 = row.insertCell(1);
      cell2.innerHTML = '<input type="text" step="0.001" id="rowmonto" name="rowmonto[]" style="width:200px;height:50px;border:0;outline:0;display:inline-block"  value="'+n+'" readonly>'; 
      var cell3 = row.insertCell(2);   
      cell3.innerHTML = cell3.innerHTML+ '<a class="btn btn-edit-entregable" onClick="editFuente(this)"><i class="fas fa-edit"></i></a> <a type="button" class="btn btn-delete-entregable" onclick="deleteFuente(this)" title="ELIMINAR"><i class="fas fa-trash-alt"></i></a>';
    

  }

  function sumar(){
      var table = document.getElementsByName("rowmonto[]");
      var suma = 0;
      for(var i=0; i<table.length;i++){
            montoString =table[i].value;
           
            monto = montoString.replace('$','');
            cantidad = monto.replaceAll(',','');
           
            valor = parseFloat(cantidad);
            
            suma = suma + valor;

      }
   
      const formatter = new Intl.NumberFormat("en-US", {style: "currency", currency: "USD"});
      const n = formatter.format(suma);
     
      document.getElementById("financiamiento").value = n;
  
   

  }

  function deleteFuente(td) {
      if (confirm('¿Estás seguro o segura de eliminar ?')) {
          row = td.parentElement.parentElement;
          document.getElementById("fuente-financiamiento").deleteRow(row.rowIndex);
          resetFuente();
          sumar();
      }

  }

  function resetFuente(){
      document.getElementById('fuenteid').value="0";
      document.getElementById('montoid').value=null;
      selectedFuenteRow  = null;
  }

  function editFuente(td){

      selectedFuenteRow  = td.parentElement.parentElement;
      console.log(document.getElementsByName("rowfuente"))
      var monto =document.getElementById("rowmonto").value;
      monto = monto.replace('$','');
      monto = monto.replaceAll(',','');
      console.log(monto + "edit fuente");
      document.getElementById('fuenteid').value=document.getElementsByName("rowfuente[]")[0].id;
      document.getElementById('montoid').value=monto;
    //  document.getElementById('montoid').value=document.getElementById("rowmonto").value;
     
 }
 
 function updateFuente(data) {
     // var monto =document.getElementById("rowmonto").value;
      monto = data.montovalue
      const formatter = new Intl.NumberFormat("en-US", {style: "currency", currency: "USD"});
       m = formatter.format(monto);
      console.log(monto);
      selectedFuenteRow.cells[0].innerHTML = '<input id="'+ data.fuentevalue +'" name="rowfuente[]" style="width:400px;height:50px;border:0;outline:0;display:inline-block" value="'+ data.fuentetext +'" readonly>'; 
      selectedFuenteRow.cells[1].innerHTML = '<input type="text" step="0.001" id="rowmonto" name="rowmonto[]" style="width:100px;height:50px;border:0;outline:0;display:inline-block"  value="'+m+'" readonly>'; 
      
      
 }

 function montoTotal(){
      
      console.log(document.getElementsByName("rowfuente[]"));

 }


 //ADD FUENTE DE FINANCIAMIENTO PERO CON ROL 'ENLACE EXTERNO'

 var selectedMontoRow = null

  function onSubmitMonto(){
      var data ={}
       
      var monto = document.getElementById('monto-entregable').value;

      var porcentaje= document.getElementById('porcentaje-total').value;
      
      data['monto']=monto;
      data['porcentaje'] = porcentaje;
   
      if(selectedMontoRow == null){
            agregar_monto(data)
      }else{
            updateMonto(data);
      }
      resetMonto();
  }
  function agregar_monto(data){
        
      var table = document.getElementById("fuente-financiamiento-externo").getElementsByTagName('tbody')[0];
      var m = data.monto;
      const formatter = new Intl.NumberFormat("en-US", {style: "currency", currency: "USD"});
      const o = formatter.format(m);
     
      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
      cell1.innerHTML = '<input type="text" id="rowmonto-externo" name="rowmonto-externo[]" style="width:400px;height:50px;border:0;outline:0;display:inline-block" value="'+ m+'" readonly>'; 
      var cell2 = row.insertCell(1);
      cell2.innerHTML = '<input type="number" id="rowporcentaje" name="rowporcentaje[]" style="width:100px;height:50px;border:0;outline:0;display:inline-block"  value="'+ data.porcentaje+'" readonly>'; 
      var cell3 = row.insertCell(2);   
      cell3.innerHTML = cell3.innerHTML+ '<a class="btn btn-edit-entregable" onClick="editMonto(this)" title="EDITAR"><i class="fas fa-edit entregable"></i></a> <a type="button" class="btn btn-delete-entregable" onclick="deleteMonto(this)" title="ELIMINAR"><i class="fas fa-trash-alt entregable"></i></a>';
      
        
  }

  function deleteMonto(td) {
      if (confirm('¿Estás seguro o segura de eliminar ?')) {
          row = td.parentElement.parentElement;
          document.getElementById("fuente-financiamiento-externo").deleteRow(row.rowIndex);
          resetMonto();
      }
  }

  function resetMonto(){
      document.getElementById('monto-entregable').value="0";
      document.getElementById('porcentaje-total').value="0";
      selectedMontoRow  = null;
  }

  function editMonto(td){

      selectedMontoRow  = td.parentElement.parentElement;

      var cantidad =document.getElementById("rowmonto-externo").value;
      cantidad = cantidad.replace('$','');
      cantidad = cantidad.replaceAll(',','');
   
      document.getElementById('monto-entregable').value=cantidad;
      document.getElementById('porcentaje-total').value=document.getElementById("rowporcentaje").value;
     
 }
 
 function updateMonto(data) {

      monto = data.monto
      const formatter = new Intl.NumberFormat("en-US", {style: "currency", currency: "USD"});
       m = formatter.format(monto);

      selectedMontoRow.cells[0].innerHTML = '<input type="text" id="rowmonto-externo" name="rowmonto-externo[]" style="width:400px;height:50px;border:0;outline:0;display:inline-block" value="'+ m +'" readonly>'; 
      selectedMontoRow.cells[1].innerHTML = '<input type="number" id="rowporcentaje"  name="rowporcentaje[]" style="width:100px;height:50px;border:0;outline:0;display:inline-block"  value="'+data.porcentaje+'" readonly>'; 
   
 }



  //funciones para obtener datos de PROGRAMAS ESPECIALES EN ADD_ENTREGABLE Y EDIT_ENTREGABLE.HTML

  function cambioPMP(value){
        
     //json objpmp transferido de twig a js y parseado
     var dataobjpmp = document.querySelector('#idobjpmp').dataset.isObjpmp;
     var objpmp = JSON.parse(dataobjpmp);
     //obtener select correspondiente a objetivopmp
     var selectobjpmp = document.getElementById('idobjpmp');

     if (value.length == 0) selectobjpmp.innerHTML = "<option></option>";
      else{
            var objpmpopt = "<option>" + "Seleccione..."+ "</option>";
            //for para leer json objpmp
            for(let i=0; i<objpmp.length; i++){
              //si la condicion se cumple asignar objetivos correspondientes a select
                if(objpmp[i]["fk_pmp"] == value ){
                  objpmpopt += "<option value='" + objpmp[i].id_obj + "'>" + objpmp[i].obj_estrategia + "</option>";                              
            }           
                  }                  
            }         
            selectobjpmp.innerHTML = objpmpopt;
  }


  function cambioEstrategia(){
      
      var dataestrategiapmp = document.querySelector('#idestrategiapmp').dataset.isEstrategiapmp;
      var estrategiapmp = JSON.parse(dataestrategiapmp);

      var objetivopmp= document.getElementById('idobjpmp');
      var valueobjetivo = objetivopmp.options[objetivopmp.selectedIndex].value;

      var selestrategiapmp = document.getElementById('idestrategiapmp');
      var valueestrategia = selestrategiapmp.options[selestrategiapmp.selectedIndex].value;

      if (valueestrategia.length == 0) selestrategiapmp.innerHTML = "<option></option>";
                  else{
                  var estrategiaopt = "<option>" + "Seleccione..."+ "</option>";
                for(let e=0; e<estrategiapmp.length; e++){
                     
                      if(valueobjetivo == estrategiapmp[e]["fk_obj"] ){
                          
                        estrategiaopt+= "<option value='" + estrategiapmp[e].id_estrategia + "'>" + estrategiapmp[e].estrategia_pmp + "</option>";    
                      }
                }
                selestrategiapmp.innerHTML = estrategiaopt;
            }
}

function cambiolineapmp(){
      
      var datalineapmp = document.querySelector('#idlineapmp').dataset.isLineapmp;
      var lineapmp = JSON.parse(datalineapmp);

      var selestrategiapmp = document.getElementById('idestrategiapmp');
      var valueestrategia = selestrategiapmp.options[selestrategiapmp.selectedIndex].value;

      var selelineapmp= document.getElementById('idlineapmp');
      var valuelineapmp = selelineapmp.options[selelineapmp.selectedIndex].value;


      if ( valuelineapmp.length == 0) selelineapmp.innerHTML = "<option></option>";
                  else{
                        
                  var lineapmpopt = "<option>" + "Seleccione..."+ "</option>";
                for(let e=0; e<lineapmp.length; e++){
                   
                      if(valueestrategia == lineapmp[e]["fk_estrategia_pmp"] ){
                        
                            lineapmpopt+= "<option value='" + lineapmp[e].id_linea_pmp + "'>" + lineapmp[e].linea_pmp + "</option>";    
                      }
                }
                selelineapmp.innerHTML =lineapmpopt;
            }
}


//funciones para enviar datos seleccionados a la tabla en la sección PROGRAMAS ESPECIALES DE ADD_ENTREGABLE.HTML Y EDIT_ENTREGABLE.HTML

function agregar_programaespecial(){

      var pmp = document.getElementById('idpmp')
      var valuepmp = pmp.options[pmp.selectedIndex].text;

      var objetivopmp= document.getElementById('idobjpmp');
      var valueobjetivo = objetivopmp.options[objetivopmp.selectedIndex].text;

      var selestrategiapmp = document.getElementById('idestrategiapmp');
      var valueestrategia = selestrategiapmp.options[selestrategiapmp.selectedIndex].text;

      var selelineapmp= document.getElementById('idlineapmp');
      var valuelineapmp = selelineapmp.options[selelineapmp.selectedIndex].text;


      var table = document.getElementById("programa-especial").getElementsByTagName('tbody')[0];
     
      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
      cell1.innerHTML = '<textarea name="pmp[]" style="width:180px;height:100px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+valuepmp+'</textarea>'; 
      var cell2 = row.insertCell(1);
      cell2.innerHTML = '<textarea name="objpmp[]" style="width:180px;height:100px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+valueobjetivo+'</textarea>'; 
      var cell3 = row.insertCell(2);
      cell3.innerHTML = '<textarea name="estrategiapmp[]" style="width:180px;height:100px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+valueestrategia+'</textarea>'; 
      var cell4 = row.insertCell(3);
      cell4.innerHTML = '<textarea name="lineapmp[]" style="width:180px;height:100px;border:0;outline:0;display:inline-block" cols="50" rows="5" readonly>'+valuelineapmp+'</textarea>'; 
      var cell5 = row.insertCell(4);   
      cell5.innerHTML = cell5.innerHTML+ '<a type="button" class="btn btn-delete-entregable" onclick="deletePmp(this)" title="ELIMINAR"><i class="fas fa-trash-alt" ></i></a>';
      resetPmp();
}

function deletePmp(td) {
      if (confirm('¿Estás seguro o segura de eliminar ?')) {
          row = td.parentElement.parentElement;
          document.getElementById("programa-especial").deleteRow(row.rowIndex);
          resetPmp();
      }
  }

  function resetPmp(){
     
      document.getElementById('idpmp').value = "Seleccione...";
     

       document.getElementById('idobjpmp').value = "Seleccione...";
     

      document.getElementById('idestrategiapmp').value = "Seleccione...";
     

     document.getElementById('idlineapmp').value = "Seleccione...";
     
  }

  //AVANCE EXTERNO
  //funciones para agregar datos seleccionados en tabla de add_program.html

var selectedRowAvance = null;

function agregarAvance(){
      var data ={}
      var selectpoblacion = document.getElementById('poblacion-externo');
      var poblacion= selectpoblacion.options[selectpoblacion.selectedIndex].value;

      var mt = document.getElementById('m_t').value;
      var ht = document.getElementById('h_t').value;
      var md = document.getElementById('m_d').value;
      var hd = document.getElementById('h_d').value;
      var mi = document.getElementById('m_i').value;
      var hi = document.getElementById('h_i').value;

      data['poblacion']=poblacion;
      data['mt']=mt;
      data['ht']=ht;
      data['md']=md;
      data['hd']=hd;
      data['mi']=mi;
      data['hi']=hi

     
      if (selectedRowAvance == null){
            agregarPoblacion(data);
           
      }else{
            updatePoblacion(data);
      }
  
      resetPoblacion();
}

function agregarPoblacion(data){
        
      var table = document.getElementById("tb-poblacion-externo").getElementsByTagName('tbody')[0];

      var row = table.insertRow(table.length);
      var cell1 = row.insertCell(0);
      cell1.innerHTML ='<div style="display:flex;flex-direction:row;justify-content:center"><input style="border:none;width:200px;color:#6E799F;height:24px" value="'+data.poblacion+'" id="poblacion-tb-externo" name="poblacion[]"/></div>';  
      var cell2 = row.insertCell(1);
      cell2.innerHTML = '<div style="display:flex;flex-direction:row;justify-content:center"><div style="display:flex;flex-direction:row"><p style="height:20px">M</p><input style="border:none;width:12px;color:#6E799F;height:24px" value="'+data.mt+'" name="m_t1[]"/></div>' + '<div style="display:flex;flex-direction:row"><p style="height:20px">H</p><input style="border:none;width:12px;color:#6E799F;height:24px" value="'+data.ht+'" name="h_t1[]"/></div></div>'
      var cell3 = row.insertCell(2);   
      cell3.innerHTML = '<div style="display:flex;flex-direction:row;justify-content:center"><div style="display:flex;flex-direction:row"><p style="height:20px">M</p><input style="border:none;width:12px;color:#6E799F;height:24px" value="'+data.md+'" name="m_d1[]"/></div>' + '<div style="display:flex;flex-direction:row"><p style="height:20px">H</p><input style="border:none;width:12px;color:#6E799F;height:24px" value="'+data.hd+'" name="h_d1[]"/></div></div>'
      var cell4 = row.insertCell(3);
      cell4.innerHTML = '<div style="display:flex;flex-direction:row;justify-content:center"><div style="display:flex;flex-direction:row"><p style="height:20px">M</p><input style="border:none;width:12px;color:#6E799F;height:24px" value="'+data.mi+'" name="m_i1[]"/></div>' + '<div style="display:flex;flex-direction:row"><p style="height:20px">H</p><input style="border:none;width:12px;color:#6E799F;height:24px" value="'+data.mi+'" name="h_i1[]"/></div></div>'
      var cell5 = row.insertCell(4);
      cell5.innerHTML= cell5.innerHTML+ '<a type="button" class="btn btn-delete-entregable" onclick="deletePoblacion(this)" title="ELIMINAR"><i class="fas fa-trash-alt entregable"></i></a>';
      resetPoblacion()
        
  }

  function deletePoblacion(td) {
      if (confirm('¿Estás seguro o segura de eliminar la población capturada?')) {
          row = td.parentElement.parentElement;
          document.getElementById("tb-poblacion-externo").deleteRow(row.rowIndex);
          resetPoblacion();
      }
  }

  function resetPoblacion(){
      document.getElementById('poblacion-externo').value= "Seleccione...";
     
      document.getElementById('m_t').value="0"
document.getElementById('h_t').value="0"
document.getElementById('m_d').value="0"
document.getElementById('h_d').value="0"
document.getElementById('m_i').value="0"
document.getElementById('h_i').value="0"

     
  }

  function cellContent() {
      var content= document.getElementById('poblacion-tb-externo').value;
      var selectpoblacion = document.getElementById('poblacion-externo');
      var poblacion= selectpoblacion.options[selectpoblacion.selectedIndex].value;

      console.log("esto es content" + content + "esto es el select" + poblacion)
 
      if(content == poblacion ){
            alert("Esta población ya ha sido capturada, por favor selecciona otro tipo de población.")
            document.getElementById('poblacion-externo').value= "Seleccione...";
      }
     }

  //INDICADORES

  function showInfoIndicador(value){


      var jsonindicador = document.querySelector('#select-indicadorid').dataset.isIndicador;
      var arrayindicador = JSON.parse(jsonindicador);

      var jsonvariables = document.querySelector('#select-indicadorid').dataset.isVariables;
      var arrayvariables = JSON.parse(jsonvariables);

      var jsonbaseMeta = document.querySelector('#select-indicadorid').dataset.isBase;
      var arraybase = JSON.parse(jsonbaseMeta);

      var jsonpp = document.querySelector('#select-indicadorid').dataset.isPp;
      var arraypp = JSON.parse(jsonpp);

      console.log("HOLA, SOY FUNCION SHOWINFOINDICADOR" + value)
      if(value.length>0){
            for(y=0; y<arrayvariables.length;y++){
                  for(b=0; b<arraybase.length;b++){
                  if(value == arrayvariables[y].fk_indicador && value == arraybase[b].fk_indicador){
                        if(arrayvariables[y].variable== 'A'){
                              document.getElementById("nombre-indicador").innerHTML=arrayvariables[y].nombre;
                              document.getElementById("linea-base").value=arraybase[b].linea_base;
                              document.getElementById("linea-meta").value=arraybase[b].meta;
                        for(x=0;x<arrayindicador.length;x++){
                              if(arrayindicador[x].id_indicador == arrayvariables[y].fk_indicador){
                                    console.log("soy el if del segundo for" + arrayindicador[x].definicion)
                                    document.getElementById("definicion-indicador").innerHTML=arrayindicador[x].definicion;
                                    for(p=0; p<arraypp.length;p++){
                                          if(arraypp[p].id_pp ==arrayindicador[x].fk_pp){
                                                document.getElementById("programa-presupuestario").innerHTML=arraypp[p].pp;
                                          }
                                          
                                    }
                              }
                        }
                        }else if(arrayvariables[y].variable == 'B'){
                              document.getElementById("variable-b").innerHTML= arrayvariables[y].nombre;
                        }else if(arrayvariables[y].variable == 'C'){
                              document.getElementById("variable-c").innerHTML= arrayvariables[y].nombre;
                        }
                       
                  }
            }
        }
    }
  }

//funcion para mostrar informacion actualizada del indicador al editar indicador
function indicadorValores (value){
      var jsonindicadores = document.querySelector('#mes-indicadores').dataset.isIndicadores;
      var indicadores = JSON.parse(jsonindicadores);

      var jsonnumero = document.querySelector('#mes-indicadores').dataset.isNumero;
      var numero = JSON.parse(jsonnumero);

      var jsonindicador = document.querySelector('#mes-indicadores').dataset.isIndicador;
      var indicador = JSON.parse(jsonindicador);

      console.log(numero);
      if(value.length > 0){
            for(i=0; i<indicadores.length;i++){
                  if(numero == indicadores[i].id_indicadores){
                        document.getElementById("año").value = indicadores[i].año
                        if(value == 'ENERO'){
                        document.getElementById("valor-b").value= indicadores[i].en_b
                        document.getElementById("valor-c").value= indicadores[i].en_c

                        document.getElementById("btn-delete-indicadores").style.display='block';
                        document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                        
                        }else if(value == 'FEBRERO'){
                              document.getElementById("valor-b").value= indicadores[i].feb_b
                              document.getElementById("valor-c").value= indicadores[i].feb_c
                              document.getElementById("btn-delete-indicadores").style.display='block';
                              document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                              }else if(value == 'MARZO'){
                                    document.getElementById("valor-b").value= indicadores[i].mar_b
                                    document.getElementById("valor-c").value= indicadores[i].mar_c
                                    document.getElementById("btn-delete-indicadores").style.display='block';
                                    document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                    }else if(value == 'ABRIL'){
                                          document.getElementById("valor-b").value= indicadores[i].ab_b
                                          document.getElementById("valor-c").value= indicadores[i].ab_c
                                          document.getElementById("btn-delete-indicadores").style.display='block';
                                          document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                          }else if(value == 'MAYO'){
                                                document.getElementById("valor-b").value= indicadores[i].may_b
                                                document.getElementById("valor-c").value= indicadores[i].may_c
                                                document.getElementById("btn-delete-indicadores").style.display='block';
                                                document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                }else if(value == 'JUNIO'){
                                                      document.getElementById("valor-b").value= indicadores[i].jun_b
                                                      document.getElementById("valor-c").value= indicadores[i].jun_c
                                                      document.getElementById("btn-delete-indicadores").style.display='block';
                                                document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                      }else if(value == 'JULIO'){
                                                            document.getElementById("valor-b").value= indicadores[i].jul_b
                                                            document.getElementById("valor-c").value= indicadores[i].jul_c
                                                            document.getElementById("btn-delete-indicadores").style.display='block';
                                                document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                            }else if(value == 'AGOSTO'){
                                                                  document.getElementById("valor-b").value= indicadores[i].ago_b
                                                                  document.getElementById("valor-c").value= indicadores[i].ago_c
                                                                  document.getElementById("btn-delete-indicadores").style.display='block';
                                                                  document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                                  }else if(value == 'SEPTIEMBRE'){
                                                                        document.getElementById("valor-b").value= indicadores[i].sep_b
                                                                        document.getElementById("valor-c").value= indicadores[i].sep_c
                                                                        document.getElementById("btn-delete-indicadores").style.display='block';
                                                                        document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                                        }else if(value == 'OCTUBRE'){
                                                                              document.getElementById("valor-b").value= indicadores[i].oct_b
                                                                              document.getElementById("valor-c").value= indicadores[i].oct_c
                                                                              document.getElementById("btn-delete-indicadores").style.display='block';
                                                                              document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                                              }else if(value == 'NOVIEMBRE'){
                                                                                    document.getElementById("valor-b").value= indicadores[i].nov_b
                                                                                    document.getElementById("valor-c").value= indicadores[i].nov_c
                                                                                    document.getElementById("btn-delete-indicadores").style.display='block';
                                                                                    document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                                                    }else if(value == 'DICIEMBRE'){
                                                                                          document.getElementById("valor-b").value= indicadores[i].dic_b
                                                                                          document.getElementById("valor-c").value= indicadores[i].dic_c
                                                                                          document.getElementById("btn-delete-indicadores").style.display='block';
                                                                                          document.getElementById("btn-delete-indicadores").innerHTML="ELIMINAR";
                                                                                          }
                  }
            }

      }

     
}

//funcion para enviar datos a eliminar en ruta de eliminar datos indicadores

function deleteData(){

      document.getElementById("valor-b").value= 0
      document.getElementById("valor-c").value= 0
      document.getElementById("btn-delete-indicadores").style.display='none';


        
}




//JS PARA PROGRESS CIRCLE BAR EN HOMEENTREGABLES
 $(".progress").each(function() {
    
        var value = $(this).attr('data-value');
        var left = $(this).find('.progress-left .progress-bar');
        var right = $(this).find('.progress-right .progress-bar');
    
        if (value > 0) {
          if (value <= 50) {
            right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
            right.css("color","rgb(230, 46, 27)")
          } else if(value >=51 && value<=100) {
            right.css('transform', 'rotate(180deg)')
            left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
            right.css("color", "rgb(0, 143, 57)")
            left.css("color","rgb(0, 143, 57)")
          }else if(value >100){
            right.css('transform', 'rotate(180deg)')
            left.css('transform','rotate(180deg)')
            right.css("color", "rgb(0, 143, 57)")
            left.css("color","rgb(0, 143, 57)")
          }
        }
    
      })
    
      function percentageToDegrees(percentage) {
    
        return percentage / 100 * 360
    
      }
    
      //FUNCION PARA SELECCIONAR INFORMACIÓN EN BUSCADOR 
     function cambio_linea_accion(value){
 
 var datalinea = document.querySelector('#linea-accion-pmp').dataset.isLinea;
 var jsonlinea = JSON.parse(datalinea);
 
 var datapmp =  document.querySelector('#linea-accion-pmp').dataset.isPmpespecial;
 var jsonpmp = JSON.parse(datapmp)

 var dataprograma = document.querySelector('#linea-accion-pmp').dataset.isProgram;
 var jsonprogram = JSON.parse(dataprograma);

 var dataentregable = document.querySelector('#linea-accion-pmp').dataset.isEntregable;
 var jsonentregable = JSON.parse(dataentregable);

 var linea = document.getElementById('linea-accion-pmp');

 if (value.length == 0) linea.innerHTML = "<option></option>";
      else{

        var lineaopt = "<option value='0'>" + "Seleccione..."+ "</option>";
        for(let p=0; p<jsonprogram.length; p++){
        for(let e=0; e<jsonentregable.length; e++){
        
                  if(jsonentregable[e]['fk_id_programa'] == jsonprogram[p]['id_programa']){
        for(let o=0; o<jsonpmp.length; o++){
           if(jsonpmp[o]['programa'] == value){

           
             if(jsonpmp[o]['fk_id_entregable'] == jsonentregable[e]['id_entregable']){
       
              lineaopt += "<option value='" + jsonpmp[o].linea_accion + "'>" + jsonpmp[o].linea_accion + "</option>";                              
             }
            }
      }
}      
}
}       
        }         
        linea.innerHTML = lineaopt;

      
     }
      function programa_principal(value){
            console.log("funcion programa principal")
            var dataprograma = document.querySelector('#linea-accion-pmp').dataset.isProgram;
            var jsonprogram = JSON.parse(dataprograma);

            var dataentregable = document.querySelector('#linea-accion-pmp').dataset.isEntregable;
            var jsonentregable = JSON.parse(dataentregable);

            var datapmp =  document.querySelector('#linea-accion-pmp').dataset.isPmpespecial;
            var jsonpmp = JSON.parse(datapmp)

            var programa = document.getElementById("programa-mapa");

            var datalinea = document.querySelector('#linea-accion-pmp').dataset.isLinea;
            var jsonlinea = JSON.parse(datalinea);
            
            var linea_accion = document.getElementById("linea-accion-pmp").value;
            console.log(linea_accion + "valor linea accion")


            if (value.length == 0) programa.innerHTML = "<option></option>"
            else{
                  var programaopt = "<option value='0'>" + "Seleccione..."+ "</option>";
               
                  for(pm=0;pm<jsonpmp.length;pm++){
                        if (value == jsonpmp[pm]['linea_accion']){
                              for(e=0;e<jsonentregable.length;e++)
                              {
                                    if(jsonentregable[e]['id_entregable'] == jsonpmp[pm]['fk_id_entregable']){
                                          for(p=0;p<jsonprogram.length;p++){
                                                if (jsonprogram[p]['id_programa']== jsonentregable[e]['fk_id_programa'] ){
                                                      console.log(jsonprogram[p]['nombre_programa'])
                                                      programaopt +="<option value='" + jsonprogram[p].nombre_programa + "'>" + jsonprogram[p].nombre_programa + "</option>";   
                                                }
                                          }
                                    }
                              }
                        }
                  }

            }
            programa.innerHTML = programaopt
      }

      function entregable_mapa(value){

            var dataprograma = document.querySelector('#linea-accion-pmp').dataset.isProgram;
            var jsonprogram = JSON.parse(dataprograma);

            var dataentregable = document.querySelector('#linea-accion-pmp').dataset.isEntregable;
            var jsonentregable = JSON.parse(dataentregable);

            var entregable = document.getElementById('entregable-mapa');
            if (value.length == 0) entregable.innerHTML = "<option></option>"
            else{
                  var entregableopt = "<option value='0'>" + "Seleccione..."+ "</option>";

                  for(p=0;p<jsonprogram.length;p++){
                        if(jsonprogram[p]['nombre_programa'] == value){
                              console.log(value)
                              for(e=0;e<jsonentregable.length;e++)
                              {
                                                if (jsonprogram[p]['id_programa'] == jsonentregable[e]['fk_id_programa']){
                                                     
                                                      entregableopt +="<option value='" + jsonentregable[e].nombre_entregable + "'>" + jsonentregable[e].nombre_entregable + "</option>";   
                                                }
                                          }
                                     }
                              }
                        }
                  

            
            entregable.innerHTML = entregableopt
      }
    
//FUNCION PARA MOSTRAR INFORMACIÓN DE MAPA.HTML

       var municipios = document.querySelectorAll('path');
       var jsonResultado = document.querySelector('#mapa-content').dataset.isResultado;
       var resultado = JSON.parse(jsonResultado);
      
       for(i=0; i<municipios.length;i++){  
           
             if(resultado != null){
                 
            for(var x=0; x<resultado.length; x++){
                  if(municipios[i].id == resultado[x].municipio){
                        console.log(resultado);
                       municipios[i].style.fill= "#695093";
                        municipios[i].addEventListener("mouseover", function(e){
                              
                              resultado.forEach(function(element,index, resultado){
                                    if(e.currentTarget.id == resultado[index].municipio){
                                          document.getElementById("mostrar").style.display="block";
                                          document.getElementById("mostrar").style.height="130" + "px";
                                          document.getElementById("mostrar").style.left=e.pageX+"px";
                                          document.getElementById("mostrar").style.top=e.pageY+"px";
                                          document.getElementById("mostrar").innerHTML=resultado[index].municipio+"<br>"+"Mujeres:\n"+resultado[index].mujeres+"<br>"
                                                                          +"\nHombres:\n"+resultado[index].hombres+
                                                                          "<br>"+"Presupuesto:\n$"+resultado[index].presupuesto;
                                    }
                              });
                        });
                             

                        municipios[i].addEventListener("mouseout",function(e){
                              document.getElementById("mostrar").style.display='none';
                        });
                  }
                
       }

       
      }
      else{
            municipios[i].addEventListener("mouseover", function(e){
                  console.log("mouseover funcionando");
                  console.log(e.currentTarget.id);
                  document.getElementById("mostrar").style.display="block";
                  document.getElementById("mostrar").style.height="30" + "px";
                  document.getElementById("mostrar").style.left=e.pageX+"px";
                  document.getElementById("mostrar").style.top=e.pageY+"px";
                  document.getElementById("mostrar").innerHTML=e.currentTarget.id;
                     
            });

            municipios[i].addEventListener("mouseout",function(e){
                  document.getElementById("mostrar").style.display='none';
            });
      }
      
}


  
    //JS PARA PDF DE ENTREGABLE
   
      function dowloadPdf(){
            

            var jsonprograma= document.querySelector('#pdf').dataset.isPrograma;
            var programa = JSON.parse(jsonprograma);

            var entregable = document.querySelector('#pdf').dataset.isEntregable;
            var jsonlocacion = document.querySelector('#pdf').dataset.isMunicipios;
            var locacion = JSON.parse(jsonlocacion);

            var mujeres = document.querySelector('#pdf').dataset.isMujeres;
            var hombres = document.querySelector('#pdf').dataset.isHombres;
            var monto = document.querySelector('#pdf').dataset.isMonto;

            var jsonavance = document.querySelector('#pdf').dataset.isAvance;
            var avance = JSON.parse(jsonavance);

            console.log(avance)

            var identregable =  document.querySelector('#pdf').dataset.isIdentregable;
            var municipioReal = []

            if(locacion != null){
                  for(i=0; i<municipios.length;i++){    
                     for(var x=0; x<locacion.length; x++){
                           if(municipios[i].id == locacion[x].municipio){
                                municipios[i].style.fill= "#695093";
                                    municipioReal.push(municipios[i].id)
                            
                           }
                      }
                }
          }

          municipioReal = municipioReal.filter(function (value, index, array) { 
            return array.indexOf(value) === index;
        });
          console.log("municipios"+municipioReal)
            var ods = document.querySelector('#pdf').dataset.isOds;
            //var pods = JSON.parse(ods);

            var porcentaje = document.querySelector('#pdf').dataset.isPorcentaje;

            var pespecial = document.querySelector('#pdf').dataset.isPespecial;
            var plinea = JSON.parse(pespecial);

            console.log(plinea)
           
            var svg = document.getElementById("yucatan");

            var logo = document.getElementById("logo-semujeres");
  
           const doc = new jsPDF();


           console.log(identregable + avance )

           var resultado = []
                        
           md = 0
           mi = 0
           hd = 0
           hi = 0
           for(let m=0; m<municipioReal.length;m++){
                 
                
                 for(let r=0;r<avance.length;r++){
                       if(municipioReal[m] == avance[r].municipio){
                             md += (avance[r].m_d + avance[r].m_ds)
                             mi += (avance[r].mi1 + avance[r].mis)
                             hd += (avance[r].hd1 + avance[r].hds)
                             hi += (avance[r].hi1 + avance[r].his)
                             
                       }
                    
                 }
                 resultado.push([municipioReal[m],md,mi,hd,hi])
     
                 md = 0
                 mi = 0
                 hd = 0
                 hi = 0
           }

          //TITULO

          
          if(logo){
          
            var l = new XMLSerializer();
            var strl = l.serializeToString(logo);
      
           var can = document.createElement('canvas');
           var cont = can.getContext('2d');
         
           let e =canvg.Canvg.fromString(cont, strl);
           e.start();
           let imgD = can.toDataURL('image/png');
           doc.addImage(imgD, 'SVG', 10,2 ,75, 40);
           }


            doc.setFontSize(18);
            doc.setTextColor(105, 80, 147);
            var titulo = doc.splitTextToSize("Sistema de Seguimiento a las Acciones para la Igualdad de Género",125);
            doc.text(titulo, 90, 20);
           
           //NOMBRE DE PROGRAMA
            doc.setFillColor(105, 80, 147);
            doc.rect(5, 30, 200, 20, "F");

            doc.setTextColor(255,255,255);
            doc.setFontSize(16);
            var pe = doc.splitTextToSize(programa[0].nombre_programa,180);
            doc.text(pe, 22, 37);
           
          //CONTENIDO
            doc.setFillColor(229, 229, 232);
            doc.rect(5, 50, 200, 230, "F");

            doc.setFillColor(255,255,255);
            doc.rect(13,55, 185, 130, "F");
             //ODS
            doc.setTextColor(105, 80, 147);
            doc.setFontSize(14);
            doc.setFontType("bold")
            doc.text("Objetivo de Desarrollo Sostenible", 25, 63);
            //CONTENIDO ODS
            doc.setTextColor(136, 136, 136);
            doc.setFontSize(12);
            doc.setFontType("normal")
            var o = doc.splitTextToSize(ods,160);
            doc.text(o, 27, 68);

              //PED
             doc.setTextColor(105, 80, 147);
             doc.setFontSize(14);
             doc.setFontType("bold")
             doc.text("Alineación al Objetivo del P.E.D.", 25, 80);
             //Contenido PED
             doc.setTextColor(136, 136, 136);
             doc.setFontSize(12);
             doc.setFontType("normal")
             doc.text("Objetivo", 27, 85);
             var obj = doc.splitTextToSize(programa[0].objetivoped,160);
             doc.text(obj, 29, 90);

             //PROGRAMAS ESPECIALES
             doc.setTextColor(105, 80, 147);
             doc.setFontSize(14);
             doc.setFontType("bold")
             doc.text("Alineación a Programas Especiales", 25,100 );
             //Contenido PROGRAMAS ESPECIALES 90
             doc.setTextColor(136, 136, 136);
             doc.setFontSize(12);
             doc.setFontType("normal")
             doc.text("Linea de Acción 1", 27, 105);
             let pmp =''
             if(plinea[0]== "Programa Especial para Igualdad de Género, Oportunidades y no Discriminación"){
                  pmp = "PEIGOND"
             }else if(plinea[0]== "Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres"){
                  pmp = "PEPASEVM"
             }else if(plinea[0] == "Programa Especial para Prevención del Embarazo en Adolescentes"){
                   pmp = "PEPEA"
             }
             var split = doc.splitTextToSize(pmp+plinea[1],160);
             doc.text(split,29,110);
            
             if(plinea.length > 2){
                  if(plinea[2]== "Programa Especial para Igualdad de Género, Oportunidades y no Discriminación"){
                        pmp = "PEIGOND"
                   }else if(plinea[2]== "Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres"){
                        pmp = "PEPASEVM"
                   }else if(plinea[2] == "Programa Especial para Prevención del Embarazo en Adolescentes"){
                         pmp = "PEPEA"
                   }
             doc.text("Linea de Acción 2", 27, 135);
             var split = doc.splitTextToSize(pmp+plinea[3],160);
             doc.text(split,29,140);
           
             }
             if(plinea.length >4){
                  if(plinea[4]== "Programa Especial para Igualdad de Género, Oportunidades y no Discriminación"){
                        pmp = "PEIGOND"
                   }else if(plinea[4]== "Programa Especial para Prevenir, Atender, Sancionar y Erradicar la Violencia contra las Mujeres"){
                        pmp = "PEPASEVM"
                   }else if(plinea[4] == "Programa Especial para Prevención del Embarazo en Adolescentes"){
                         pmp = "PEPEA"
                   }
             doc.text("Linea de Acción 3", 27, 165);
             var split = doc.splitTextToSize(pmp+plinea[5],160);
             doc.text(split,29,170);
             }

            
            //CONTENIDO ENTREGABLE
            doc.setFillColor(255,255,255);
            doc.rect(13, 190, 185, 80, "F");
           // Entregable
             doc.setTextColor(105, 80, 147);
             doc.setFontSize(14);
             doc.setFontType("bold")
              doc.text("Entregable", 25, 200);
              //Nombre de Entregable
             doc.setTextColor(136, 136, 136);
             doc.setFontSize(12);
             doc.setFontType("normal")
             var en = doc.splitTextToSize(entregable,80);
             doc.text(en,29 , 205);

             //AVANCE
            doc.setTextColor(105, 80, 147);
            doc.setFontSize(14);
             doc.setFontType("bold")
            doc.text("Avance", 25, 230);
            doc.setFontSize(12);
             doc.setFontType("normal")
            doc.setTextColor(136, 136, 136);
            doc.text(porcentaje+"%", 29, 235);

            //AVANCE
            doc.setTextColor(105, 80, 147);
            doc.setFontSize(14);
            doc.setFontType("bold")
            var po = doc.splitTextToSize("Mujeres Beneficiadas",40);
              doc.text(po, 120, 200);
              doc.setTextColor(136, 136, 136);
              doc.setFontSize(12);
              doc.setFontType("normal")
              doc.text(mujeres, 160, 205);

             doc.setTextColor(105, 80, 147);
             doc.setFontSize(14);
             doc.setFontType("bold")
             var po = doc.splitTextToSize("Hombres Beneficiados",40);
               doc.text(po, 120, 220);
               doc.setTextColor(136, 136, 136);
               doc.setFontSize(12);
               doc.setFontType("normal")
               doc.text(hombres, 160, 225);

               doc.setTextColor(105, 80, 147);
               doc.setFontSize(14);
               doc.setFontType("bold")
               var po = doc.splitTextToSize("Monto Ejercido",40);
                 doc.text(po, 120, 240);
                 doc.setTextColor(136, 136, 136);
               doc.setFontSize(12);
               doc.setFontType("normal")
               doc.text("$"+monto, 160, 240);

              doc.addPage()
              doc.setFillColor(229, 229, 232);
              doc.rect(5, 10, 200, 278, "F");
  
              doc.setFillColor(255,255,255);
              doc.rect(13,20, 185, 130, "F");
            //MAPA
            doc.setTextColor(105, 80, 147);

            
            doc.setFontSize(14);
            doc.setFontType("bold")
              doc.text("Cobertura Municipal",80, 30)

              if(svg){

                  var s = new XMLSerializer();
                  var str = s.serializeToString(svg);
               
                 var canvas = document.createElement('canvas');
                 var context = canvas.getContext('2d');
               
                 let v =canvg.Canvg.fromString(context, str);
                 v.start();
                 let imgData = canvas.toDataURL('image/png');
                 doc.addImage(imgData, 'SVG', 25, 30, 160, 125);
                 }
             
                 /*doc.setFillColor(255,255,255);
                 doc.rect(13, 160, 185, 120, "F");

                 doc.setTextColor(105, 80, 147);
                 doc.setFontSize(12);
                 doc.setFontType("bold")
                   doc.text("Municipio", 24, 165);
                   doc.setFontType("normal")
                  
                  /* doc.setTextColor(136, 136, 136);
                   doc.setFontSize(10);
                   doc.setFontType("normal")
                   doc.text(mujeres, 160, 205);

                   doc.setTextColor(105, 80, 147);
                   doc.setFontSize(12);
                   doc.setFontType("bold")
                   var po = doc.splitTextToSize("Mujeres con discapacidad",30);
                     doc.text(po, 54, 165);
                     doc.setFontType("normal")
                   

                     doc.setTextColor(105, 80, 147);
                     doc.setFontSize(12);
                     doc.setFontType("bold")
                     var po = doc.splitTextToSize("Hombres con discapacidad",30);
                       doc.text(po, 84, 165);

                       doc.setTextColor(105, 80, 147);
                       doc.setFontSize(12);
                       doc.setFontType("bold")
                       var po = doc.splitTextToSize("Mujeres hablantes de lengua indígena",40);
                         doc.text(po, 114, 165);


                         doc.setTextColor(105, 80, 147);
                         doc.setFontSize(12);
                         doc.setFontType("bold")
                         var po = doc.splitTextToSize("Hombres hablantes de lengua indígena",40);
                           doc.text(po, 154, 165);*/
                                 
                            
                           

                             

            window.open(URL.createObjectURL(doc.output("blob")))

      }


   