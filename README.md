📂 Sistema de Registro y Seguimiento de Documentos
👨‍💻 Desarrollado por
Ricaldi Solis Maylon
Curi Javier Dayana
Santos Tocas Fernando
🚀 Descripción del proyecto

Sistema web para el registro, seguimiento y distribución de documentos, permitiendo controlar su flujo entre despachos, generar guías de remisión y mantener un historial completo de estados.

🛠️ Tecnologías utilizadas
PHP
MySQL
JavaScript
HTML / CSS
PhpSpreadsheet (Composer)
🗄️ Base de datos
CREATE DATABASE sistema_documentos;
USE sistema_documentos;
📌 Tabla despacho
CREATE TABLE despacho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

INSERT INTO despacho (nombre) VALUES
('Fiscalía 1'),
('Fiscalía 2'),
('Administración'),
('Logística');
📄 Tabla documento
CREATE TABLE documento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    tipo VARCHAR(50),
    fecha_recepcion DATE,
    remitente VARCHAR(100),
    id_despacho INT,
    estado VARCHAR(50),
    FOREIGN KEY (id_despacho) REFERENCES despacho(id)
);
🚚 Tabla guia_remito
CREATE TABLE guia_remito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_guia VARCHAR(20) UNIQUE NOT NULL,
    fecha DATE,
    id_despacho INT,
    FOREIGN KEY (id_despacho) REFERENCES despacho(id)
);
📦 Tabla detalle_guia
CREATE TABLE detalle_guia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_guia INT,
    id_documento INT,
    FOREIGN KEY (id_guia) REFERENCES guia_remito(id),
    FOREIGN KEY (id_documento) REFERENCES documento(id)
);
📊 Tabla seguimiento
CREATE TABLE seguimiento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_documento INT,
    estado VARCHAR(50),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_documento) REFERENCES documento(id)
);
📤 Importación de Excel

El sistema permite la carga masiva de documentos mediante archivos Excel (.xlsx / .xls) usando PhpSpreadsheet (Composer).

🔥 Funcionalidades
Registro de documentos
Control de estados
Historial de seguimiento
Generación de guías de remisión
Importación masiva desde Excel
Filtros y búsqueda
📌 Nota

Cada cambio de estado se guarda automáticamente en la tabla de seguimiento para mantener el historial completo del documento.
