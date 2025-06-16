# Simulatore di Visite Reali

Questo progetto, sviluppato da **Bocaletto Luca** (account GitHub: `bocaletto-luca`), è una webapp PHP che simula pagine web visitate in modalità reali. La simulazione viene effettuata tramite un reverse proxy integrato che carica l'URL target in un `<iframe>`, fornendo una simulazione di traffico "reale".

## Caratteristiche

- **Reverse Proxy Integrato:** Utilizza un parametro GET (`?proxy=1&url=...`) per recuperare il contenuto di un URL specifico.  
  - Valida l'URL e utilizza cURL per effettuare la richiesta.
  - Rimuove eventuali meta tag `Content-Security-Policy` dal contenuto HTML per evitare restrizioni.
- **Interfaccia Utente:** 
  - Form per specificare l'URL target, l'intervallo in secondi tra le ricariche e il numero di visite da simulare.
  - Log in tempo reale che mostra le ricariche effettuate.
  - Pulsanti per avviare, mettere in pausa e fermare la simulazione.
- **Interazione dinamica:**  
  - L'`<iframe>` viene creato dinamicamente e carica l'URL target sempre tramite il proxy, consentendo un aggiornamento continuo della pagina target.
  - La simulazione gestisce stati di pausa/ripresa e consente l'interruzione manuale tramite l'interfaccia.

## Come Funziona

1. **Richiesta Proxy:**  
   Se il parametro `proxy=1` e `url` sono presenti nella query string, lo script PHP agisce come proxy:
   - Verifica la validità dell'URL.
   - Effettua una richiesta cURL per recuperare il contenuto della pagina target.
   - Rimuove eventuali meta tag CSP per evitare restrizioni.
   - Restituisce il contenuto HTML con l'intestazione corretta.

2. **Interfaccia della Simulazione:**  
   - L'utente inserisce l'URL target, l'intervallo delle ricariche (in secondi) e il numero di visite da simulare.
   - Viene creato un `<iframe>` che carica l'URL target tramite il reverse proxy.
   - Un contatore monitora le ricariche e, ad ogni aggiornamento, viene registrata una nuova "visita" nel log.
   - Funzionalità per mettere in pausa, riprendere o fermare la simulazione.

## Prerequisiti

- **PHP:** Assicurarsi di avere una versione di PHP installata sul proprio server.
- **cURL:** L'estensione cURL deve essere abilitata in PHP.
- **Internet:** La webapp utilizza Bootstrap tramite CDN per lo styling e il bundle JavaScript.

## Installazione

1. **Clona il Repository**

   ```bash
   git clone https://github.com/bocaletto-luca/nome-del-repository.git
   cd nome-del-repository
   ```

2. **Configurazione del Server**

   - Copia i file sul tuo server web con supporto PHP.
   - Assicurati che l'estensione cURL sia attiva.

3. **Accesso alla Webapp**

   - Accedi al file tramite browser, ad esempio: `http://tuo-dominio.com/path/to/index.php`.

## Utilizzo

1. **Inserisci l'URL:** Digita l’URL della pagina da caricare (es. `https://esempio.com`).
2. **Intervallo:** Imposta il numero di secondi tra le ricariche.
3. **Numero di Visite:** Specifica il numero totale di visite da simulare.
4. **Controlli:**  
   - Clicca su **Avvia** per iniziare la simulazione.
   - Utilizza **Metti in pausa** per sospendere temporaneamente la simulazione e **Riprendi** per continuare.
   - Il pulsante **Ferma** interrompe completamente il processo e rimuove l'`<iframe>`.

## Struttura del Codice

- **Sezione PHP Iniziale:**  
  Gestisce il comportamento del reverse proxy controllando i parametri GET `proxy` e `url`. Utilizza cURL per effettuare la richiesta e processa il documento HTML (rimuovendo meta tag CSP).

- **Interfaccia HTML e CSS:**  
  Utilizza Bootstrap per lo styling con alcune regole CSS personalizzate per il layout del log e dell'`<iframe>`.

- **Logica JavaScript:**  
  - Gestisce le operazioni temporizzate per simulare le visite.
  - Aggiorna dinamicamente il log con l'orario e lo stato delle ricariche.
  - Permette azioni di pausa, ripresa e interruzione della simulazione.

## Contributi

Se intendi contribuire, puoi inviare un pull request o aprire una issue sul repository GitHub.

## Avvertenze

- **Uso Responsabile:**  
  Utilizza questa webapp responsabilmente, rispettando le politiche dei siti target. La simulazione di visite non autorizzate su siti web di terzi può violare i termini di servizio.

---
