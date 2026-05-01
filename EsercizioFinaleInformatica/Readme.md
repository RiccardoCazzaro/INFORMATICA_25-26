# Futsal Mania

### Cazzaro Riccardo

Sito web dedicato al mondo del Futsal (calcio a 5) italiano

---

## Funzionalità

- **Informazioni sul Futsal**: storia dello sport, regole, campioni, club italiani, campi di gioco
- **Shop online (solo lettura)** con sconto del 10% per gli utenti registrati
- **Classifiche e risultati** dei campionati creati
- **Area di gestione** per creare squadre, campionati e registrare partite
- **Sistema di autenticazione** con registrazione, login e logout

---

## Tipologie di Utente

| Ruolo                  | Accessi                                                 | Funzioni esclusive                      |
|  ---                   |                           ---                           |              ---                        |
| **Utente non loggato** | `Home, Storia, Campi, Shop (prezzo pieno), Classifiche` |               —                         |
| **Utente loggato**     | Tutte le sezioni + sconto shop + `gestione`             | Aggiunta/eliminazione squadre e partite |
| **Proprietario**       | Tutte le sezioni precedenti +  `creaCampionato`         | Creazione e gestione campionati         |

---

## Struttura del Progetto

```
esercizioFinaleInformatica/
├── home.php con css
├── frameworkFile/
│   ├── headerChoice.php          
│   ├── headerUser.php con css            
│   ├── headerProprietario.php con css
│   ├── headerAdmin.php con css          
│   ├── footer.php con css
│   ├── dbHandler.php             
│   ├── logOut.php 
│   └── file.json    
|        
├── adminFunzioni/
│   ├── gestione.php con css              
│   ├── creaCampionato.php con css       
│   ├── classifiche.php con css           
|
├── loginFile/
│   └── loginForm.php con css, login.php
|
├── signupFile/
│   └── signupForm.php con css, signup.php
|
├── campiFile/
│   └── campi.php con css
|
├── storiaFile/
│   └── storia.php con css
|
└── shopFile/
    └── shop.php con css
```

---

### Calcolo punti nella classifica del campionato
All'inserimento di una partita i punti vengono aggiornati automaticamente nella tabella `squadra`:
- **Vittoria** → 3 punti
- **Pareggio** → 1 punto
- **Sconfitta** → 0 punti

---

Le statistiche per ogni squadra :
- PG (partite giocate)
- GF(gol fatti) 
- GS (gol subiti) V (vittorie)
- P (pareggi)
- S(sconfitte)
- DR (differena reti) 

---

 
## Database
 
Il database si chiama `futsalhouse` (MySQL) `mysql:host=localhost;dbname=futsalhouse;charset=utf8`
 
| Tabella      | Descrizione                         | Colonne principali                                                                    |
|---           |---                                  |---                                                                                    |
| `utente`     | Utenti registrati                   | `idUtente`, `nome`, `email`, `passwordUt`, ` ruolo ENUM('utente', 'proprietario', 'admin') DEFAULT 'admin',`, `cognome`, `dataNascita`, `CAP`, `provincia`, `nazionalità`, `telefono`, `ruolo`                                                             |
| `campionato` | Campionati creati                   | `idCampionato`, `nomeCampionato`, `dataCreazione`                                     |
|`squadra`     | Squadre iscritte a un campionato    | `idSquadra`, `nomeSquadra`, `città`, `punti`, `idCampionato`, `idUtente`, `nomePalazzetto`, `nazionalità`                                                                                                                                |
| `partita`    | Risultati delle gare                | `idPartita`, `idSquadraCasa`, `idSquadraOspite`, `golCasa`, `golOspite`, `dataPartita`, `oraPartita`, `idUtente`, `idCampionato` |
 
## Regole generali/logiche
- se cancelli un campionato si cancellano anche le squadre presenti in quel campionato
- non si può creare una partita tra due squadre di diverso campionato
- quando si cancella una squadra anche le sue partite verranno cancellate e di conseguenza i gol e i punti della squadra avversaria verranno aggiornati
- non puoi chiamare ugualmente due squadre nello stesso campionato e due campionati diversi
