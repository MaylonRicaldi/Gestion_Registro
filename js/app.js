document.addEventListener("DOMContentLoaded", function(){

// =============================
// CAMBIAR SECCIÓN
// =============================
function mostrar(seccion){
    document.getElementById("dashboard").style.display = "none";
    document.getElementById("registro").style.display = "none";
    document.getElementById("lista").style.display = "none";
    document.getElementById("guias").style.display = "none";

    document.getElementById(seccion).style.display = "block";

    if(seccion === "guias"){
        cargarGuias();
    }
}

// =============================
// VALIDACIÓN FORMULARIO
// =============================
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

// =============================
// VARIABLES (TU LÓGICA)
// =============================
let datosGlobales = [];
let pagina = 1;
const porPagina = 10;

// =============================
// CARGAR DATOS (USAMOS TU MÉTODO)
// =============================
fetch("listar.php")
.then(res => res.json())
.then(data => {
    datosGlobales = data;
    renderizarTabla();           // 🔥 TU TABLA
    actualizarDashboard(data);   // 🔥 TU DASHBOARD
});

// =============================
// RENDERIZAR TABLA (TUYA)
// =============================
function renderizarTabla(){

    let tabla = document.getElementById("tabla");
    tabla.innerHTML = "";

    let codigo = document.getElementById("buscarCodigo").value.trim();
    let despacho = document.getElementById("filtroDespacho").value;
    let estado = document.getElementById("filtroEstado").value;

    let filtrados = datosGlobales.filter(doc => {

        let numeroDoc = String(parseInt(doc.codigo.replace("DOC", "")));
        let numeroBusqueda = codigo;

        return (
            (codigo === "" || numeroDoc.startsWith(numeroBusqueda)) &&
            (despacho === "" || doc.despacho === despacho) &&
            (estado === "" || doc.estado === estado)
        );
    });

    let inicio = (pagina - 1) * porPagina;
    let fin = inicio + porPagina;

    let paginaDatos = filtrados.slice(inicio, fin);

    paginaDatos.forEach(doc => {
        tabla.innerHTML += `
        <tr>
            <td>
                <input type="checkbox" name="documentos[]" value="${doc.id}"
                ${(doc.estado === "Cargo de envio" ||
                doc.estado === "Cargo devuelto entregado" ||
                doc.estado === "No recepcionado") ? "disabled" : ""}>
            </td>
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
}

// =============================
// FILTROS (TUYOS)
// =============================
document.getElementById("buscarCodigo").addEventListener("input", () => {
    pagina = 1;
    renderizarTabla();
});

document.getElementById("filtroDespacho").addEventListener("change", () => {
    pagina = 1;
    renderizarTabla();
});

document.getElementById("filtroEstado").addEventListener("change", () => {
    pagina = 1;
    renderizarTabla();
});

// =============================
// CAMBIAR ESTADO
// =============================
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

// =============================
// DASHBOARD (TUYO)
// =============================
function actualizarDashboard(data){
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
    });

    document.getElementById("totalDocs").innerText = total;
    document.getElementById("pendientes").innerText = pendientes;
    document.getElementById("enviados").innerText = enviados;
    document.getElementById("entregados").innerText = entregados;
    document.getElementById("noRecibidos").innerText = noRecibidos;
}

// =============================
// GENERAR GUÍA (DEL OTRO CÓDIGO)
// =============================
document.getElementById("formGuia")?.addEventListener("submit", function(e){
    e.preventDefault();

    let checks = document.querySelectorAll("input[name='documentos[]']:checked");

    if(checks.length === 0){
        alert("Selecciona al menos un documento");
        return;
    }

    let formData = new FormData();

    checks.forEach(c => {
        formData.append("documentos[]", c.value);
    });

    fetch("generar_guia.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(res => {
        alert(res);
        setTimeout(() => location.reload(), 1500);
    });
});

// =============================
// GUIAS
// =============================
function cargarGuias(){
    fetch("listar_guias.php")
    .then(res => res.json())
    .then(data => {

        let tabla = document.getElementById("tablaGuias");
        tabla.innerHTML = "";

        data.forEach(g => {
            tabla.innerHTML += `
            <tr>
                <td>${g.numero_guia}</td>
                <td>${g.fecha}</td>
                <td>${g.despacho}</td>
            </tr>
            `;
        });
    });
}

// =============================
// GLOBAL
// =============================
window.mostrar = mostrar;
window.cambiarEstado = cambiarEstado;

});