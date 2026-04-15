// CAMBIAR SECCIÓN
function mostrar(seccion){
    document.getElementById("dashboard").style.display = "none";
    document.getElementById("registro").style.display = "none";
    document.getElementById("lista").style.display = "none";

    document.getElementById(seccion).style.display = "block";
}

// VALIDACIÓN
document.getElementById("formRegistro").addEventListener("submit", function(e){

    let numero = document.querySelector("[name='numero']").value.trim();

    if(numero === "" || isNaN(numero)){
        alert("Ingrese un número válido");
        e.preventDefault();
        return;
    }

    if(numero < 1 || numero > 999){
        alert("Número debe estar entre 1 y 999");
        e.preventDefault();
    }
});

// LISTAR DATOS
fetch("listar.php")
.then(res => res.json())
.then(data => {

    let tabla = document.getElementById("tabla");

    let total = data.length;
    let pendientes = 0;
    let enviados = 0;
    let entregados = 0;
    let noRecibidos = 0;

    data.forEach(doc => {

        if(doc.estado === "Pendiente de entrega") pendientes++;
        if(doc.estado === "Cargo de envio") enviados++;
        if(doc.estado === "Cargo devuelto entregado") entregados++;
        if(doc.estado === "No recepcionado") noRecibidos++;

        tabla.innerHTML += `
        <tr>
            <td><input type="checkbox" name="documentos[]" value="${doc.id}"></td>
            <td>${doc.codigo}</td>
            <td>${doc.tipo}</td>
            <td>${doc.fecha_recepcion}</td>
            <td>${doc.remitente}</td>
            <td>${doc.despacho}</td>
            <td>
                <select onchange="cambiarEstado(${doc.id}, this.value)">
                    <option value="Pendiente de entrega" ${doc.estado=="Pendiente de entrega"?"selected":""}>Pendiente</option>
                    <option value="Cargo de envio" ${doc.estado=="Cargo de envio"?"selected":""}>Enviado</option>
                    <option value="Cargo devuelto entregado" ${doc.estado=="Cargo devuelto entregado"?"selected":""}>Entregado</option>
                    <option value="No recepcionado" ${doc.estado=="No recepcionado"?"selected":""}>No recibido</option>
                </select>
            </td>
        </tr>
        `;
    });

    document.getElementById("totalDocs").innerText = total;
    document.getElementById("pendientes").innerText = pendientes;
    document.getElementById("enviados").innerText = enviados;
    document.getElementById("entregados").innerText = entregados;
    document.getElementById("noRecibidos").innerText = noRecibidos;
});

// CAMBIAR ESTADO
function cambiarEstado(id, estado){
    fetch("actualizar_estado.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id + "&estado=" + encodeURIComponent(estado)
    })
    .then(() => location.reload());
}