-- Active: 1776593716148@@127.0.0.1@3306@mysql@futsalhouse
CREATE DATABASE IF NOT EXISTS futsalhouse;

USE futsalhouse;

-- Tabella UTENTI
CREATE TABLE utenti(
    idUtente INT AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) ,
    dataNascita DATE,
    CAP INT,
    passwordUt VARCHAR(255) NOT NULL,
    provincia VARCHAR(255),
    nazionalità VARCHAR(50),
    email VARCHAR(255) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    ruolo ENUM("proprietario", "admin") DEFAULT "admin",
    PRIMARY KEY(idUtente)
);

-- Tabella CAMPIONATO 
CREATE TABLE campionato (
    idCampionato INT AUTO_INCREMENT,
    nomeCampionato VARCHAR(255) NOT NULL,
    dataCreazione DATE,
    PRIMARY KEY(idCampionato)
);

-- Tabella SQUADRA
CREATE TABLE squadra(
    idSquadra INT AUTO_INCREMENT,
    nomeSquadra VARCHAR(255) NOT NULL,
    città VARCHAR(255),
    coloriSocietari VARCHAR(255),
    nazionalità VARCHAR(50),
    nomePalazzetto VARCHAR(255),
    idUtente INT,
    idCampionato INT,
    punti INT DEFAULT 0,
    PRIMARY KEY(idSquadra),
    FOREIGN KEY (idCampionato) REFERENCES campionato(idCampionato) ON DELETE CASCADE,
    FOREIGN KEY (idUtente) REFERENCES utenti(idUtente) ON DELETE SET NULL
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
    FOREIGN KEY (idUtente) REFERENCES utenti(idUtente) ON DELETE SET NULL,
    FOREIGN KEY (idSquadraCasa) REFERENCES squadra(idSquadra) ON DELETE CASCADE,
    FOREIGN KEY (idSquadraOspite) REFERENCES squadra(idSquadra) ON DELETE CASCADE,
    FOREIGN KEY (idCampionato) REFERENCES campionato(idCampionato) ON DELETE CASCADE
);

drop DATABASE IF EXISTS futsalHouse;
