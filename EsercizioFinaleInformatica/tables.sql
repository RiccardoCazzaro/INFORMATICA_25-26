create database futsalHouse;
use futsalHouse;

create Table campionato(
    idCampionato int auto_increment,
    titolo varchar(255) not null,
    annataStagione YEAR(4),
    categoria varchar(255),
    regione varchar(255),
    nazionalita varchar(50),
    primary key(idCampionato)
);

create table classifica(
    idClassifica int(20) auto_increment,
    giornata DATE(20),
    idCampionato int(20),
    idSquadra int(20),
    punti int(20),
    primary key(idClassifica),
    foreign key(idCampionato) references campionato(idCampionato),
    foreign key(idSquadra) references squadra(idSquadra)
);

create tables squadra(
    idSquadra int(20) auto_increment,
    idPalazzetto int(20),
    nomeSquadra varchar(255) not null,
    città varchar(255),
    email varchar(255),
    coloriSocietari varchar(255),
    dataNascita DATE(20),
    nazionalita varchar(50),
    logo VARCHAR(255),
    linkInstagram VARCHAR(255),
    Foreign Key (idPalazzetto) REFERENCES palazzetto(idPalazzetto),
    primary key(idSquadra)
);

create Table palazzetto(
    idPalazzetto int(20) auto_increment,
    nomePalazzetto varchar(255) not null,
    superficie varchar(255),
    città varchar(255),
    indirizzo varchar(255),
    gpsLatitudine varchar(255),
    gpsLongitudine varchar(255),
    cap int(20),
    primary key(idPalazzetto)
);

create tables partita(
    idPartita int(20) auto_increment,
    idCampionato int(20),
    idSquadraCasa int(20),
    idSquadraOspite int(20),
    dataPartita DATE(20),
    oraPartita TIME(20),
    golSquadraCasa int(20),
    golSquadraOspite int(20),
    idMembro int(20),
    primary key(idPartita),
    Foreign Key (idMembro) REFERENCES membro(idMembro),
    foreign key(idCampionato) references campionato(idCampionato),
    foreign key(idSquadraCasa)references squadra(idSquadra),
    foreign key(idSquadraOspite) references squadra(idSquadra)
);

create table giocatore (
    idGiocatore int(20) auto_increment,
    idSquadra int(20),
    nome varchar(255) not null,
    cognome varchar(255) not null,
    dataNascita DATE(20),
    nazionalita varchar(50),
    ruolo varchar(255),
    nMaglia int(20),
    altezza int(20),
    peso int(20),
    piedeForte varchar(255),
    goalSegnati int(20),
    assist int(20),
    ammonizioni int(20),
    espulsioni int(20),
    primary key(idGiocatore),
    foreign key(idSquadra) references squadra(idSquadra)
    primary key(idGiocatore),
    foreign key(idSquadra) references squadra(idSquadra)
);

create table marcatore(
    idMarcatore int(20) auto_increment,
    idPartita int(20),
    idGiocatore int(20),
    minuto int(20),
    idMembro int(20),
    primary key(idMarcatore),
    Foreign Key (idUtente) REFERENCES ()
    foreign key(idPartita) references partita(idPartita),
    foreign key(idGiocatore) references giocatore(idGiocatore)
)

create tables utenti(
    idUtente int(20) auto_increment,
    nome varchar(255) not null,
    cognome varchar(255) not null,
    dataNascita DATE(20),
    CAP int(6),
    passwordUt varchar(255),
    provincia varchar(255),
    nazionalità varchar(50),
    email varchar(255),
    telefono varchar(20),
    primary key(idUtente)
);

create tables prodotti(
    idProdotto int(20) auto_increment,
    nomeProdotto varchar(255) not null,
    descrizione varchar(255),
    categoria varchar(255),
    prezzo decimal(10,2),
    giacenza int(20),
    primary key(idProdotto)
);


CREATE VIEW classisficaCampionato AS 
SELECT c.titolo, s.nomeSquadra, cl.punti
FROM classifica cl
JOIN campionato c USING(idCampionato)
JOIN squadra s USING(idSquadra)
ORDER BY cl.punti DESC;

CREATE VIEW dettagliPartita AS  
SELECT p.dataPartita, p.oraPartita, sCasa.nomeSquadra AS squadraCasa, sOspite.nomeSquadra AS squadraOspite, p.golSquadraCasa, p.golSquadraOspite
FROM partita p
JOIN squadra sCasa ON p.idSquadraCasa = sCasa.idSquadra
JOIN squadra sOspite ON p.idSquadraOspite = sOspite.idSquadra
ORDER BY p.dataPartita DESC, p.oraPartita DESC;

CREATE VIEW giocatoriSquadra AS
SELECT s.nomeSquadra, g.nome, g.cognome, g.ruolo, g.nMaglia, g.goalSegnati, g.assist, g.altezza, g.peso
FROM giocatore g
JOIN squadra s ON g.idSquadra = s.idSquadra
ORDER BY s.nomeSquadra, g.nome, g.cognome;

CREATE VIEW marcatorePartita AS
SELECT p.dataPartita, p.oraPartita, sCasa.nomeSquadra AS squadraCasa, sOspite.nomeSquadra AS squadraOspite, g.nome, g.cognome, m.minuto
FROM marcatore m
JOIN partita p ON m.idPartita = p.idPartita
JOIN giocatore g ON m.idGiocatore = g.idGiocatore
JOIN squadra sCasa ON p.idSquadraCasa = sCasa.idSquadra
JOIN squadra sOspite ON p.idSquadraOspite = sOspite.idSquadra
ORDER BY p.dataPartita DESC, p.oraPartita DESC, m.minuto ASC;





