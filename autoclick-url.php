<?php
// Se viene passato il parametro "proxy=1" e "url", agisci come reverse proxy.
if (isset($_GET['proxy']) && $_GET['proxy'] == "1" && isset($_GET['url'])) {
    $url = $_GET['url'];
    // Valida l'URL
    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        header("HTTP/1.1 400 Bad Request");
        echo "Parametro 'url' obbligatorio o non valido.";
        exit;
    }
    
    // Utilizza cURL per recuperare il contenuto della pagina target
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Utilizza un User-Agent "normale"
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Proxy)");
    $response = curl_exec($ch);
    $error   = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response === false || $httpCode !== 200) {
        header("HTTP/1.1 500 Internal Server Error");
        echo "Errore durante il recupero del contenuto: " . $error;
        exit;
    }
    
    // Rimuovi eventuali meta tag CSP dal contenuto HTML
    $response = preg_replace('/<meta[^>]+http-equiv=["\']Content-Security-Policy["\'][^>]*>/i', '', $response);
    
    // Imposta il Content-Type corretto (per semplicità usiamo text/html)
    header("Content-Type: text/html; charset=UTF-8");
    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Simulatore di Visite (Proxy PHP + iFrame)</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 20px;
      background: #f8f9fa;
    }
    #log {
      max-height: 250px;
      overflow-y: auto;
      background: #fff;
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 5px;
      margin-top: 20px;
    }
    #log p {
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      word-wrap: break-word;
    }
    #iframeContainer {
      margin-top: 20px;
      height: 500px;
    }
    #iframeContainer iframe {
      width: 100%;
      height: 100%;
      border: 1px solid #ddd;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="mb-4 text-center">Simulatore di Visite Reali</h1>
    
    <form id="visitForm">
      <div class="mb-3">
        <label for="url" class="form-label">URL da caricare</label>
        <input type="url" id="url" class="form-control" placeholder="https://esempio.com" required>
      </div>
      <div class="mb-3">
        <label for="interval" class="form-label">Intervallo fra ricariche (secondi)</label>
        <input type="number" id="interval" class="form-control" min="1" placeholder="Inserisci l'intervallo" required>
      </div>
      <div class="mb-3">
        <label for="count" class="form-label">Numero di visite da simulare</label>
        <input type="number" id="count" class="form-control" min="1" placeholder="Inserisci il numero di visite" required>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Avvia</button>
        <button type="button" id="pauseBtn" class="btn btn-warning" disabled>Metti in pausa</button>
        <button type="button" id="stopBtn" class="btn btn-danger" disabled>Ferma</button>
      </div>
      <p class="mt-3 text-muted">
        Nota: L'iframe caricherà l'URL target tramite questo stesso file PHP in modalità proxy.  
        Ogni ricarica dell’iframe genera un caricamento completo della pagina target.
      </p>
    </form>
    
    <div id="log"></div>
    
    <div id="iframeContainer">
      <!-- L'iframe verrà creato dinamicamente -->
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    let timerRef = null;
    let currentVisit = 0;
    let totalVisits = 0;
    let intervalSeconds = 0;
    let paused = false;
    let targetUrl = "";
    let iframeEl = null;
    
    // In questo esempio, il nostro proxy è lo stesso file, che si attiva con ?proxy=1&url=...
    const proxyScript = window.location.origin + window.location.pathname + "?proxy=1&url=";

    const logDiv = document.getElementById("log");
    const visitForm = document.getElementById("visitForm");
    const pauseBtn = document.getElementById("pauseBtn");
    const stopBtn = document.getElementById("stopBtn");
    const iframeContainer = document.getElementById("iframeContainer");

    function addLog(message) {
      const p = document.createElement("p");
      p.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
      logDiv.appendChild(p);
      logDiv.scrollTop = logDiv.scrollHeight;
    }
    
    function simulateVisit() {
      if (currentVisit >= totalVisits) {
        addLog("Terminato il numero previsto di visite.");
        resetButtons();
        return;
      }
      if (paused) return;
      
      currentVisit++;
      addLog(`Ricarica ${currentVisit} di ${totalVisits}.`);
      
      if (iframeEl) {
        // Riassegna lo src utilizzando il proxy
        iframeEl.src = proxyScript + encodeURIComponent(targetUrl);
      }
      timerRef = setTimeout(simulateVisit, intervalSeconds * 1000);
    }
    
    function resetButtons() {
      pauseBtn.disabled = true;
      stopBtn.disabled = true;
      visitForm.querySelector('button[type="submit"]').disabled = false;
      paused = false;
      pauseBtn.textContent = "Metti in pausa";
    }
    
    visitForm.addEventListener("submit", function(e) {
      e.preventDefault();
      targetUrl = document.getElementById("url").value.trim();
      intervalSeconds = parseInt(document.getElementById("interval").value);
      totalVisits = parseInt(document.getElementById("count").value);
      
      if (!targetUrl || isNaN(intervalSeconds) || isNaN(totalVisits) ||
          intervalSeconds < 1 || totalVisits < 1) {
        alert("Compila tutti i campi con valori validi.");
        return;
      }
      
      // Reset log e variabili
      logDiv.innerHTML = "";
      currentVisit = 0;
      paused = false;
      
      pauseBtn.disabled = false;
      stopBtn.disabled = false;
      visitForm.querySelector('button[type="submit"]').disabled = true;
      pauseBtn.textContent = "Metti in pausa";
      
      // Rimuovi eventuale iframe precedente
      if (iframeEl) {
        iframeContainer.removeChild(iframeEl);
      }
      
      // Crea l'iframe che carica la pagina target tramite il proxy (lo stesso file con ?proxy=1)
      iframeEl = document.createElement("iframe");
      iframeEl.src = proxyScript + encodeURIComponent(targetUrl);
      // L'attributo sandbox è opzionale; lo usiamo per limitare potenzialmente alcuni comportamenti
      iframeEl.setAttribute("sandbox", "allow-same-origin allow-scripts allow-forms");
      iframeContainer.appendChild(iframeEl);
      
      addLog(`Simulazione avviata per ${targetUrl}. Prima visita registrata.`);
      // La prima apertura viene considerata già registrata:
      currentVisit = 1;
      timerRef = setTimeout(simulateVisit, intervalSeconds * 1000);
    });
    
    pauseBtn.addEventListener("click", function() {
      if (paused) {
        paused = false;
        pauseBtn.textContent = "Metti in pausa";
        addLog("Ripresa delle visite.");
        simulateVisit();
      } else {
        paused = true;
        clearTimeout(timerRef);
        pauseBtn.textContent = "Riprendi";
        addLog("Visite messe in pausa.");
      }
    });
    
    stopBtn.addEventListener("click", function() {
      clearTimeout(timerRef);
      paused = false;
      addLog("Simulazione interrotta dall'utente.");
      resetButtons();
      if (iframeEl) {
        iframeContainer.removeChild(iframeEl);
        iframeEl = null;
      }
    });
  </script>
  
  <!-- Bootstrap Bundle JS (include Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
