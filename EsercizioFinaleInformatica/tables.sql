-- Creazione Database
CREATE DATABASE IF NOT EXISTS futsalHouse;
USE futsalHouse;

-- Tabella UTENTI
CREATE TABLE utenti(
    idUtente INT AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    dataNascita DATE,
    CAP INT,
    passwordUt VARCHAR(255),
    provincia VARCHAR(255),
    nazionalità VARCHAR(50),
    email VARCHAR(255) UNIQUE,
    telefono VARCHAR(20),
    PRIMARY KEY(idUtente)
);

-- Tabella SQUADRA
CREATE TABLE squadra(
    idSquadra INT AUTO_INCREMENT,
    nomeSquadra VARCHAR(255) NOT NULL,
    città VARCHAR(255),
    coloriSocietari VARCHAR(255),
    dataNascita DATE,
    nazionalità VARCHAR(50),
    logo VARCHAR(255),
    nomePalazzetto VARCHAR(255),
    idUtente INT,
    PRIMARY KEY(idSquadra),
    FOREIGN KEY (idUtente) REFERENCES utenti(idUtente) on DELETE SET NULL
);

-- Tabella CLASSIFICA
CREATE TABLE classifica (
    idSquadra INT PRIMARY KEY,
    punti INT DEFAULT 0,
    FOREIGN KEY (idSquadra) REFERENCES squadra(idSquadra) ON DELETE CASCADE
);

-- Tabella PARTITA
CREATE TABLE partita(
    idPartita INT AUTO_INCREMENT,
    idCampionato INT,
    idSquadraCasa INT,
    idSquadraOspite INT,
    dataPartita DATE,
    oraPartita TIME,
    golSquadraCasa INT DEFAULT 0,
    golSquadraOspite INT DEFAULT 0,
    idUtente INT,
    PRIMARY KEY(idPartita),
    FOREIGN KEY (idUtente) REFERENCES utenti(idUtente) on DELETE SET NULL,
    FOREIGN KEY (idSquadraCasa) REFERENCES squadra(idSquadra) on delete CASCADE,
    FOREIGN KEY (idSquadraOspite) REFERENCES squadra(idSquadra) on delete CASCADE
);

-- Tabella GIOCATORE   forse da agg  nn ancora aggiunta
CREATE TABLE giocatore (
    idGiocatore INT AUTO_INCREMENT,
    idSquadra INT,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    dataNascita DATE,
    nazionalita VARCHAR(50),
    ruolo VARCHAR(255),
    nMaglia INT,
    altezza INT,
    peso INT,
    piedeForte VARCHAR(255),
    PRIMARY KEY(idGiocatore),
    FOREIGN KEY(idSquadra) REFERENCES squadra(idSquadra) on delete CASCADE,
    UNIQUE(nome, cognome, idSquadra)
);

DROP DATABASE futsalHouse;