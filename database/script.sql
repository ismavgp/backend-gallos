
create table users (
    id serial primary key,
    name varchar(100) not null,
    email varchar(100) not null unique,
    password varchar(255) not null,
        phone varchar(15) not null,
    address varchar(255) not null,
    city varchar(100) not null,
    country varchar(100) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);



create table gallos(
    id serial primary key,
    placa varchar(20) not null unique,
    name varchar(100) not null,
    sexo char(1) not null,
    fecha_nacimiento date not null,
    url_imagen varchar(255),
    color varchar(50) not null,
    peso decimal(5,2) not null,
    talla decimal(5,2) not null,
    color_patas varchar(50) not null,
    tipo_cresta varchar(50) not null,
    id_padre int references gallos(id) null,
    id_madre int references gallos(id) null
);
create table peleas(
    id serial primary key,
    id_gallo int not null references gallos(id) on delete cascade,
    fecha timestamp not null,
    lugar varchar(255) not null,
    estado varchar(50) not null check (estado in('Ganada', 'Perdida', 'Empatada')),
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);

create table vacunas(
    id serial primary key,
    id_gallo int not null references gallos(id) on delete cascade,
    nombre_vacuna varchar(100) not null,
    fecha_aplicacion date not null,
    dosis varchar(50) not null,
    observaciones text,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);

create table entrenamientos(
    id serial primary key,
    id_gallo int not null references gallos(id) on delete cascade,
    fecha timestamp not null,
    duracion interval not null,
    tipo_entrenamiento varchar(100) not null,
    observaciones text,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);
