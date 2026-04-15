# 📂 Sistema de Registro y Distribución de Documentos

Sistema web desarrollado para la gestión, control y seguimiento de documentos dentro de una institución. Permite registrar, distribuir y monitorear documentos de manera eficiente.

---

## 👨‍💻 Desarrollado por

- 👤 Ricaldi Solis Maylon  
- 👤 Javier Curi Dayana  
- 👤 Santos Tocas Fernando  

---

## 🎨 Presentación del Proyecto

Puedes ver la presentación completa en Canva aquí:

👉 https://canva.link/wu9fyg57h86n5qj  

---

## 🗄️ Creación de la Base de Datos

```sql
CREATE DATABASE sistema_documentos;
USE sistema_documentos;

CREATE TABLE despacho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

INSERT INTO despacho (nombre) VALUES
('Fiscalía 1'),
('Fiscalía 2'),
('Administración'),
('Logística');

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

CREATE TABLE guia_remito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_guia VARCHAR(20) UNIQUE NOT NULL,
    fecha DATE,
    id_despacho INT,
    FOREIGN KEY (id_despacho) REFERENCES despacho(id)
);

CREATE TABLE detalle_guia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_guia INT,
    id_documento INT,
    FOREIGN KEY (id_guia) REFERENCES guia_remito(id),
    FOREIGN KEY (id_documento) REFERENCES documento(id)
);

CREATE TABLE seguimiento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_documento INT,
    estado VARCHAR(50),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_documento) REFERENCES documento(id)
);
