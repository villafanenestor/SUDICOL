create table almacenes
(
    cons          int auto_increment
        primary key,
    nombre        varchar(100) null,
    observaciones varchar(200) null,
    estado        varchar(20)  null,
    encargado     varchar(30)  null
)
    charset = utf8;

create table ciudades
(
    cons            int auto_increment
        primary key,
    nombre          varchar(100) null,
    id_departamento varchar(20)  null,
    tipo            varchar(20)  null,
    codpostalciud   varchar(10)  null
)
    collate = utf8_unicode_ci;

create table clientes
(
    cons                     int auto_increment
        primary key,
    nombre                   varchar(250)                 null,
    NIT                      varchar(100)                 null,
    Direccion                varchar(200)                 null,
    Ciudad                   varchar(100)                 null,
    Telefono                 varchar(100)                 null,
    Direccioncorrespondencia varchar(100)                 null,
    Actividadeconomica       varchar(200)                 null,
    Representantelegal       varchar(100)                 null,
    Cargo                    varchar(100)                 null,
    EscrituraNo              varchar(100)                 null,
    EscrituraFecha           varchar(100)                 null,
    EscrituraNotaria         varchar(100)                 null,
    EscrituraCiudad          varchar(100)                 null,
    RegistroCamaradeComercio varchar(100)                 null,
    CamaraFecha              varchar(100)                 null,
    ComprasNombre            varchar(200)                 null,
    ComprasCargo             varchar(200)                 null,
    ComprasTelefono          varchar(200)                 null,
    ComprasCorreo            varchar(200)                 null,
    FinancieraNombre         varchar(200)                 null,
    FinancieraCargo          varchar(200)                 null,
    FinancieraTelefono       varchar(200)                 null,
    FinancieraCorreo         varchar(200)                 null,
    FinancieraCiudad         varchar(200)                 null,
    TipoContribuyente        varchar(100)                 null,
    CiudadICA                varchar(100)                 null,
    Fechafacturas            varchar(100)                 null,
    Correofacturacion        varchar(200)                 null,
    AccionariaNombre         varchar(200)                 null,
    AccionariaCC             varchar(200)                 null,
    AccionariaDireccion      varchar(200)                 null,
    AccionariaTelefono       varchar(200)                 null,
    AccionariaCiudad         varchar(200)                 null,
    Accionariaacc            varchar(200)                 null,
    Banco                    varchar(200)                 null,
    BancoCuenta              varchar(200)                 null,
    BancoSucursal            varchar(200)                 null,
    BancoTelefono            varchar(200)                 null,
    BancoCiudad              varchar(200)                 null,
    ProveedoresRazonsocial   varchar(200)                 null,
    ProveedoresDireccion     varchar(200)                 null,
    ProveedoresTelefonos     varchar(200)                 null,
    ProveedoresCiudad        varchar(200)                 null,
    ClientesRazonsocial      varchar(200)                 null,
    ClientesDireccion        varchar(200)                 null,
    ClientesTelefonos        varchar(200)                 null,
    ClientesCiudad           varchar(100)                 null,
    Cupocredito              varchar(100)                 null,
    PlazoPago                varchar(100)                 null,
    CupocreditoObservaciones varchar(200)                 null,
    soporte1                 varchar(100)                 null comment '1.  Certificado de existencia y representación legal (Cámara de Comercio – 30 días de expedición)',
    soporte2                 varchar(100)                 null comment '2.  Fotocopia de Cédula de ciudadanía Representante Legal',
    soporte3                 varchar(100)                 null comment '3.  Estados financieros últimos dos (2) cortes fiscales (Balance general y Estado de resultados)',
    soporte4                 varchar(100)                 null comment '4. Copia RUT',
    soporte5                 varchar(100)                 null comment '5.  Fotocopia últimas dos (2) declaraciones de renta',
    estado                   varchar(20) default 'Activo' null,
    fechaguardado            date                         null
);

create table clientesold
(
    cons          int auto_increment
        primary key,
    nombre        varchar(150) null,
    direccion     varchar(150) null,
    correo        varchar(150) null,
    telefono      varchar(50)  null,
    contacto      varchar(150) null,
    nit           varchar(20)  null,
    bancarios     varchar(150) null,
    observaciones varchar(150) null,
    fechaingreso  date         null,
    estado        varchar(20)  null
)
    charset = utf8;

create table cotizaciones
(
    cons           int auto_increment
        primary key,
    usuario        varchar(30)                     null,
    fecharealizado date                            null,
    formadepago    varchar(20)                     null,
    fechaentrega   date                            null,
    fechadocierre  date                            null,
    cotizaciones   varchar(100)                    null,
    estado         varchar(20) default 'Pendiente' null,
    fechaestado    date                            null,
    codcliente     int                             null,
    observaciones  varchar(200)                    null,
    departamento   varchar(100)                    null,
    valor          int                             null,
    ordencompra    varchar(50)                     null,
    fecharecpecion date                            null,
    costo          int                             null,
    plazo          varchar(10)                     null,
    subcliente     varchar(150)                    null
)
    charset = utf8;

create table cotizacioneselementos
(
    cons             int auto_increment
        primary key,
    codordendecompra int           null,
    codelemento      int           null,
    codtalla         int           null,
    cantidad         int default 0 null,
    recibidos        int default 0 null,
    valor            int           null,
    costo            int           null
)
    charset = utf8;

create table departamentos
(
    cons   int auto_increment
        primary key,
    nombre varchar(100) null
)
    collate = utf8_unicode_ci;

create table elementos
(
    cons          int auto_increment
        primary key,
    Nombre        varchar(100) null,
    TipoElemento  varchar(30)  null,
    codTipoTalla  int          null,
    Observaciones varchar(150) null
)
    charset = utf8;

create table empleados
(
    Cedula         varchar(20)  not null
        primary key,
    Nombre         varchar(100) null,
    Telefono       varchar(50)  null,
    Genero         varchar(20)  null,
    codProyecto    int          null,
    codCargo       int          null,
    Camisa         varchar(10)  null,
    Pantalon       varchar(10)  null,
    Zapatos        varchar(10)  null,
    Estado         varchar(15)  null,
    FechaContratoI date         null,
    FechaContratoF date         null,
    Labor          varchar(200) null,
    Observaciones  varchar(200) null
)
    charset = utf8;

create table ordendecompra
(
    cons           int auto_increment
        primary key,
    usuario        varchar(30)                     null,
    fecharealizado date                            null,
    formadepago    varchar(20)                     null,
    fechaentrega   date                            null,
    fechadocierre  date                            null,
    cotizaciones   varchar(100)                    null,
    estado         varchar(20) default 'Pendiente' null,
    fechaestado    date                            null,
    codcliente     int                             null,
    observaciones  varchar(200)                    null,
    departamento   varchar(100)                    null,
    valor          int                             null,
    ordencompra    varchar(50)                     null,
    fecharecpecion date                            null,
    costo          int                             null,
    plazo          varchar(10)                     null,
    subcliente     varchar(150)                    null
)
    charset = utf8;

create table ordendecompraelementos
(
    cons             int auto_increment
        primary key,
    codordendecompra int           null,
    codelemento      int           null,
    procesos         text          null,
    codtalla         int           null,
    cantidad         int default 0 null,
    recibidos        int default 0 null,
    valor            int           null,
    costo            int           null
)
    charset = utf8;

create table ordendecompraprocesos
(
    cons                      int auto_increment
        primary key,
    codordendecompraelementos int          null,
    proceso                   varchar(100) null,
    cantidad                  int          null,
    realizado                 int          null,
    codproveedor              varchar(100) null,
    fechaasignado             date         null,
    fechaterminado            date         null,
    usuarioasinado            varchar(100) null,
    observacion               varchar(100) null,
    fechainicial              date         null,
    fechafinal                date         null,
    fechaestimada             date         null
);

create table procesos
(
    nombre varchar(100) not null
        primary key
);

create table proveedores
(
    cons          int auto_increment
        primary key,
    nombre        varchar(150) null,
    direccion     varchar(150) null,
    correo        varchar(150) null,
    telefono      varchar(50)  null,
    contacto      varchar(150) null,
    nit           varchar(20)  null,
    bancarios     varchar(150) null,
    observaciones varchar(150) null,
    fechaingreso  date         null,
    estado        varchar(20)  null
)
    charset = utf8;

create table tallas
(
    cons   int auto_increment
        primary key,
    nombre varchar(80) null
)
    collate = utf8_spanish_ci;

create table tallasdetalle
(
    cons     int auto_increment
        primary key,
    nombre   varchar(80) null,
    codtalla int         null
)
    collate = utf8_spanish_ci;

create table usuarios
(
    usuario varchar(100)                 not null
        primary key,
    nombre  varchar(200)                 null,
    pass    varchar(50)                  null,
    tipo    varchar(50)                  null,
    correo  varchar(100)                 null,
    estado  varchar(20) default 'Activo' null
);

