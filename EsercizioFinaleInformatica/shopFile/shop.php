
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$is_logged = isset($_SESSION['idUtente']); //ti da vero o falso a seconda se è loggato

function mostraPrezzo($prezzo) {
    global $is_logged;
    if ($is_logged) {
        $prezzoScontato = $prezzo * 0.90; //(10%)
        echo '<div class="priceContainer">';
        // Prezzo originale 
        echo '  <span class="oldPrice">€' . number_format($prezzo, 2, ',') . '</span>';
        // Prezzo nuovo scontato
        echo '  <span class="newPrice">€' . number_format($prezzoScontato, 2, ',') . '</span>';
        echo '  <span class="promo">-10%</span>';
        echo '</div>';
    } else {
        // Prezzo normale senza sconto
        echo '<h4 class="priceNormal">€' . number_format($prezzo, 2, ',') . '</h4>';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal: Storia e Passione</title>
    <link rel="stylesheet" href="shop.css?v=<?php echo time(); ?>">
</head>
<body>
        <div class="intestazione">
            <h1>Shop online</h1>
            <div class="linkShop">
                <a href="#prodottiGenerali">Prodotti Generali</a>
                <a href="#prodottiPortiere">Prodotti Portiere</a>
                <a href="#prodottiGiocatoriMovimento">Prodotti Giocatori Movimento</a>
            </div>
        </div>

        <div class="presentazioneMercato">
          <h1>Benvenuti nello Shop del Futsal</h1>
          <p>
              Scopri la nostra selezione esclusiva di attrezzatura professionale. 
              <br>
              Dalla protezione dei portieri all'agilità dei giocatori di movimento, 
              abbiamo tutto ciò che ti serve per dominare il campo
              <br>
          <strong>Qualità, resistenza e passione in ogni prodotto</strong>
       </p>

       <?php if(!$is_logged) { ?>
            <div class="avvisoSconto">
                <p><strong>Consiglio:</strong> Accedi al tuo account per sbloccare lo <span>SCONTO DEL 10%</span> su tutto il catalogo!</p>
            </div>
        <?php } 
        else { ?>
            <div class="confermaSconto">
                <p>Benvenuto <strong><?php echo $_SESSION['nome']; ?></strong>! Il tuo sconto fedeltà del 10% è stato applicato automaticamente</p>
            </div>
        <?php 
        } ?>
    </div>
    
       </div>



        <div class="prodottiSezione" id="prodottiGenerali">
            <h2>Prodotti Generali</h2>
            <div class="prodotto">
                <img src="../imgShop/imgScarpe.jpg" alt="Scarpe Futsal">
                <h3>Scarpe da Futsal</h3>
                <p>Suola in gomma specifica per superfici indoor</p>
                <?php mostraPrezzo(59.99); ?>
                <button id="scarpe">Aggiungi ai preferiti</button>
            </div>

                <div class="prodotto">
                    <img src="../imgShop/imgFasciaCapitano.jpg" alt="Fascia Capitano">
                    <h3>Fascia da Capitano</h3>
                    <p>Design distintivo per il leader in campo</p>
                    <?php mostraPrezzo(9.99); ?>
                    <button id="fasciaCapitano">Aggiungi ai preferiti</button>
                </div>

            <div class="prodotto">
                <img src="../imgShop/imgNastro.jpg" alt="Nastro Atletico">
                <h3>Nastro Atletico</h3>
                <p>Supporto resistente per polsi e dita</p>
                <?php mostraPrezzo(7.99); ?>
                <button id="nastro">Aggiungi ai preferiti</button>
            </div>

           <div class="prodotto">
                <img src="../imgShop/imgBorsa.jpg" alt="Borsa Sport">
                <h3>Borsa Sport</h3>
                <p>Capiente e resistente, con scomparti separati</p>
                <?php mostraPrezzo(44.99); ?>
                <button id="borsa">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgOcchiali.jpg" alt="Occhiali Protettivi">
                <h3>Occhiali Protettivi</h3>
                <p>Protezione oculare per sport ad alta intensità</p>
                <?php mostraPrezzo(29.99); ?>
                <button id="occhiali">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgPallone.jpg" alt="Pallone Futsal">
                <h3>Pallone Futsal</h3>
                <p>Taglia 4 a rimbalzo controllato</p>
                <?php mostraPrezzo(24.99); ?>
                <button id="pallone">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgRullo.jpg" alt="Rullo Massaggio">
                <h3>Rullo Foam Roller</h3>
                <p>Per massaggio muscolare post-gara</p>
                <?php mostraPrezzo(18.99); ?>
                <button id="rullo">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgCinesini.jpg" alt="Cinesini">
                <h3>Set Cinesini</h3>
                <p>Accessori per allenamento e agilità</p>
                <?php mostraPrezzo(12.99); ?>
                <button id="cinesini">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgConi.jpg" alt="Coni">
                <h3>Set coni</h3>
                <p>Accessori per allenamento e agilità</p>
                <?php mostraPrezzo(12.99); ?>
                <button id="coni">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgCorda.jpg" alt="Corda Salto">
                <h3>Corda per Saltare</h3>
                <p>Strumento essenziale per migliorare resistenza e coordinazione</p>
                <?php mostraPrezzo(9.99); ?>
                <button id="corda">Aggiungi ai preferiti</button>
            </div>

             <div class="prodotto">
                <img src="../imgShop/imgParastinchi.jpg" alt="Parastinchi">
                <h3>Parastinchi</h3>
                <p>Accessori per allenamento e agilità.</p>
                <?php mostraPrezzo(12.99); ?>
                <button id="parastinchi">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgBorraccia.jpg" alt="Borraccia">
                <h3>Borraccia Sportiva</h3>
                <p>Contenitore per bevande durante l'allenamento.</p>
                <?php mostraPrezzo(8.99); ?>
                <button id="borraccia">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgCerotti.jpg" alt="Cerotti Sportivi">
                <h3>Cerotti Sportivi</h3>
                <p>Per prevenire vesciche e irritazioni durante il gioco.</p>
                <?php mostraPrezzo(5.99); ?>
                <button id="cerotti">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgMaschera.jpg" alt="Maschera Protettiva">
                <h3>Maschera Protettiva</h3>
                <p>Per proteggere il viso in caso di infortuni pregressi.</p>
                <?php mostraPrezzo(19.99); ?>
                <button id="maschera">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgSpray.jpg" alt="Spray Rinfrescante">
                <h3>Spray Rinfrescante Muscolare</h3>
                <p>Per un sollievo immediato dopo l'attività fisica.</p>
                <?php mostraPrezzo(12.99); ?>
                <button id="spray">Aggiungi ai preferiti</button>
            </div>
        </div>



        <div class="prodottiSezione" id="prodottiPortiere">
            <h2>Portieri</h2>
            <div class="prodotto">
                <img src="../imgShop/imgGuantiPortieri.jpg" alt="Guanti Pro">
                <h3>Guanti da Portiere Pro</h3>
                <p>Grip avanzato e stecche di protezione dita.</p>
                <?php mostraPrezzo(49.99); ?>
                <button id="guantiPortieri">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgGinocchiere.jpg" alt="Ginocchiere">
                <h3>Ginocchiere Futsal</h3>
                <p>Protezione specifica per parquet e sintetico.</p>
                <?php mostraPrezzo(25.00); ?>
                <button id="ginocchiere">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgConchiglia.jpg" alt="Conchiglia">
                <h3>Conchiglia Protettiva</h3>
                <p>Massima protezione nelle fasi di gioco ravvicinate.</p>
                <?php mostraPrezzo(15.00); ?>
                <button id="conchiglia">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgSottoPantaloncini.jpg" alt="sottopantaloncini">
                <h3>Sottopantaloncini Imbottiti</h3>
                <p>Protezione extra per i fianchi durante i tuffi.</p>
                <?php mostraPrezzo(34.99); ?>
                <button id="sottopantaloncini">Aggiungi ai preferiti</button>
            </div>

            <div class="prodotto">
                <img src="../imgShop/imgGomitiere.jpg" alt="Gomitiere">
                <h3>Gomitiere</h3>
                <p>Protezione per i gomiti</p>
                <?php mostraPrezzo(34.99); ?>
                <button id="gomitiere">Aggiungi ai preferiti</button>
            </div>

        </div>


        <div class="prodottiSezione" id="prodottiGiocatoriMovimento">
            <h2>Giocatori di Movimento</h2>
            <div class="prodotto">
                <img src="../imgShop/imgParastinchiMini.jpg" alt="imgParastinchiMini">
                <h3>Parastinchi Leggeri</h3>
                <p>Protezione stinchi, modalità leggera</p>
                <?php mostraPrezzo(14.99); ?>
                <button id="parastinchi">Aggiungi ai preferiti</button>
            </div>

        <div class="prodotto">
                <img src="../imgShop/imgCalzeGrip.jpg" alt="Calze Antiscivolo">
                <h3>Calze Grip Tecniche</h3>
                 <p>Inserti in silicone per evitare scivolamenti nella scarpa</p>
                <?php mostraPrezzo(15.99); ?>
                <button id="calze">Aggiungi ai preferiti</button>
            </div>

</div>


<?php if($is_logged): ?>
<script>
    // Seleziona tutti i bottoni dei prodotti
    const bottoni = document.querySelectorAll('.prodotto button');

    //  Quando carichi la pagina, recupera i preferiti 
    bottoni.forEach(btn => {
        const id = btn.id;
        // Controlla se questo ID era stato salvato come "attivo"
        if (localStorage.getItem(id) == 'attivo') {
            btn.classList.add('attivo');
            btn.innerText = "Aggiunto ai preferiti";
        }

        // Quando clicchi, salva o cancella 
        btn.onclick = function() {
            this.classList.toggle('attivo');
            
            if (this.classList.contains('attivo')) {
                this.innerText = "Aggiunto ai preferiti";
                localStorage.setItem(id, 'attivo'); // Salva nel browser
            } else {
                this.innerText = "Aggiungi ai preferiti";
                localStorage.removeItem(id); // Rimuove dal browser
            }
        }
    });
</script>

<?php endif; ?>
</body>
</html>