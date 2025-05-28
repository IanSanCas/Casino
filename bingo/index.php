<?php
session_start();

// Inicializar la sesión para almacenar los números extraídos y el cartón
if (!isset($_SESSION['bingo_numbers'])) {
    $_SESSION['bingo_numbers'] = array();
}
if (!isset($_SESSION['bingo_card'])) {
    $_SESSION['bingo_card'] = null;
}

/**
 * Genera un cartón de bingo español.
 * El ticket tiene 3 filas x 9 columnas con 15 números (5 por fila) 
 * distribuido de modo que cada columna estará vinculada a un rango:
 *   - Columna 0: 1–9  
 *   - Columna 1: 10–19  
 *   - Columna 2: 20–29  
 *   - Columna 3: 30–39  
 *   - Columna 4: 40–49  
 *   - Columna 5: 50–59  
 *   - Columna 6: 60–69  
 *   - Columna 7: 70–79  
 *   - Columna 8: 80–90  
 *
 * Se procede de la siguiente forma:
 * 1. Se genera una plantilla (matriz 3×9) en la que cada fila tiene 5 celdas marcadas.
 *    Se repite la asignación hasta que cada columna tenga al menos una celda marcada.
 * 2. Para cada columna se asignan números aleatorios del rango correspondiente, 
 *    en cantidad igual a la cantidad de casillas activas de dicha columna.
 * 3. Finalmente se ordenan en cada columna (de arriba a abajo) y se asignan a la plantilla.
 */
function generateSpanishBingoCard() {
    $rows = 3;
    $cols = 9;
    $numbersPerRow = 5; // cada fila tendrá 5 números

    // Generar plantilla: matriz binaria de 3x9.
    // Cada fila debe tener exactamente 5 celdas (1 = número, 0 = vacío)
    // Repetir hasta que cada columna tenga al menos un 1.
    do {
        $template = array();
        for ($r = 0; $r < $rows; $r++){
            $template[$r] = array_fill(0, $cols, 0);
            // Seleccionar aleatoriamente 5 columnas para asignar un número en esta fila.
            $colsArray = range(0, $cols - 1);
            shuffle($colsArray);
            $chosen = array_slice($colsArray, 0, $numbersPerRow);
            foreach ($chosen as $c){
                $template[$r][$c] = 1;
            }
        }
        // Verificar que cada columna tenga al menos 1.
        $colOk = true;
        for ($c = 0; $c < $cols; $c++){
            $colSum = 0;
            for ($r = 0; $r < $rows; $r++){
                $colSum += $template[$r][$c];
            }
            if ($colSum < 1) {
                $colOk = false;
                break;
            }
        }
    } while(!$colOk);

    // Rangos para cada columna.
    $ranges = array(
        0 => range(1, 9),
        1 => range(10, 19),
        2 => range(20, 29),
        3 => range(30, 39),
        4 => range(40, 49),
        5 => range(50, 59),
        6 => range(60, 69),
        7 => range(70, 79),
        8 => range(80, 90)
    );
    
    // Asignar números a cada columna según la plantilla.
    $colNumbers = array();
    for ($c = 0; $c < $cols; $c++){
        $count = 0;
        for ($r = 0; $r < $rows; $r++){
            if ($template[$r][$c] === 1) {
                $count++;
            }
        }
        $pool = $ranges[$c];
        shuffle($pool);
        $selected = array_slice($pool, 0, $count);
        sort($selected); // ordenar ascendentemente
        $colNumbers[$c] = $selected;
    }
    
    // Construir el cartón final (matriz 3x9) usando la plantilla.
    $card = array();
    for ($r = 0; $r < $rows; $r++){
        $card[$r] = array();
        for ($c = 0; $c < $cols; $c++){
            if ($template[$r][$c] === 1) {
                $card[$r][$c] = array_shift($colNumbers[$c]);
            } else {
                $card[$r][$c] = ""; // Celda vacía
            }
        }
    }
    return $card;
}

// Procesar acciones vía GET
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'draw') {
        // Acción: extraer un número del 1 al 90
        $available = array();
        for ($i = 1; $i <= 90; $i++){
             if (!in_array($i, $_SESSION['bingo_numbers'])) {
                 $available[] = $i;
             }
        }
        if (count($available) > 0){
             $number = $available[array_rand($available)];
             $_SESSION['bingo_numbers'][] = $number;
             echo json_encode(array('number' => $number, 'drawn' => $_SESSION['bingo_numbers']));
        } else {
             echo json_encode(array('error' => 'No quedan números disponibles.'));
        }
        exit();
    } elseif ($action == 'generateCard') {
        // Acción: generar un cartón español y guardarlo en la sesión.
        $card = generateSpanishBingoCard();
        $_SESSION['bingo_card'] = $card;
        echo json_encode(array('card' => $card));
        exit();
    } elseif ($action == 'reset') {
        // Acción: reiniciar el juego borrando los números extraídos.
        $_SESSION['bingo_numbers'] = array();
        echo json_encode(array('status' => 'reset'));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bingo Español - Elige Tu Cartón</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Estilos generales */
        body {
            background: #222;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        /* Tablero general para mostrar los 90 números */
        .board {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 5px;
            margin-bottom: 20px;
        }
        .board .cell {
            background: #444;
            padding: 8px;
            text-align: center;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .board .cell.drawn {
            background: #ffcc00;
            color: #222;
            font-weight: bold;
        }
        .controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            background: #ffcc00;
            border: none;
            color: #222;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            margin: 5px;
        }
        .btn:hover {
            background: #e6b800;
        }
        .drawn-numbers {
            text-align: center;
            margin-top: 20px;
        }
        .drawn-numbers span {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            background: #444;
            border-radius: 4px;
        }
        /* Estilos para el Cartón Español */
        .card-container {
            text-align: center;
            margin-top: 40px;
        }
        .bingo-card {
            margin: 0 auto;
            border-collapse: collapse;
        }
        .bingo-card th, .bingo-card td {
            border: 1px solid #555;
            width: 40px;
            height: 40px;
            text-align: center;
            vertical-align: middle;
            font-size: 16px;
            transition: background 0.3s;
        }
        .bingo-card td.marked {
            background: #ffcc00;
            color: #222;
            font-weight: bold;
        }
        .bingo-card th {
            background: #333;
            color: #ffcc00;
        }
        @media (max-width: 480px) {
            .bingo-card th, .bingo-card td {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
        }
    </style>
    <!-- Google Fonts (opcional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Bingo Español</h1>
    
    <!-- Tablero General: muestra las 90 bolas -->
    <h2>Tablero General</h2>
    <div class="board" id="bingoBoard">
        <?php for ($i = 1; $i <= 90; $i++): ?>
            <div class="cell" id="cell-<?php echo $i; ?>"><?php echo $i; ?></div>
        <?php endfor; ?>
    </div>
    <div class="controls">
        <button class="btn" id="drawButton">Sacar Número</button>
        <!-- Botón de reiniciar para poder reiniciar manualmente -->
        <button class="btn" id="resetButton">Reiniciar Juego</button>
    </div>
    <div class="drawn-numbers" id="drawnNumbers">
        <h2>Números Extraídos:</h2>
        <div id="numbersList"></div>
    </div>
    
    <!-- Sección para generar y mostrar el cartón español -->
    <div class="card-container">
        <h2>Tu Cartón</h2>
        <button class="btn" id="generateCardButton">Elegir Mi Cartón</button>
        <div id="cardDisplay" style="margin-top:20px;"></div>
    </div>
</div>

<script>
    let playerCard = null;
    
    // Renderizar el cartón en la sección correspondiente
    function renderCard(card) {
        let html = '<table class="bingo-card"><tr>';
        // Cabecera con índices (opcional)
        for (let i = 1; i <= 9; i++){
            html += '<th>' + i + '</th>';
        }
        html += '</tr>';
        for (let row = 0; row < 3; row++){
            html += '<tr>';
            for (let col = 0; col < 9; col++){
                let cellValue = card[row][col];
                let cellId = "card-cell-" + row + "-" + col;
                if(cellValue === ""){
                    html += '<td id="'+cellId+'"></td>';
                } else {
                    html += '<td id="'+cellId+'">' + cellValue + '</td>';
                }
            }
            html += '</tr>';
        }
        html += '</table>';
        document.getElementById('cardDisplay').innerHTML = html;
    }
    
    // Actualiza el marcado del cartón con los números extraídos
    function updateCardMarking(drawnNumbers) {
        if (!playerCard) return;
        for (let row = 0; row < 3; row++){
            for (let col = 0; col < 9; col++){
                let cellId = "card-cell-" + row + "-" + col;
                let cell = document.getElementById(cellId);
                if (!cell) continue;
                let cellValue = cell.innerText;
                if (cellValue !== "" && drawnNumbers.includes(parseInt(cellValue))) {
                    cell.classList.add("marked");
                }
            }
        }
    }
    
    // Evento para generar el cartón
    document.getElementById('generateCardButton').addEventListener('click', function(){
        fetch('?action=generateCard')
        .then(response => response.json())
        .then(data => {
            if (data.card) {
                playerCard = data.card;
                renderCard(playerCard);
                // Actualizar el marcado si ya hay números extraídos
                let drawnNumbers = [];
                document.querySelectorAll('#numbersList span').forEach(span => {
                    drawnNumbers.push(parseInt(span.innerText));
                });
                updateCardMarking(drawnNumbers);
            }
        })
        .catch(error => console.error('Error al generar el cartón:', error));
    });
    
    // Evento para extraer un número
    document.getElementById('drawButton').addEventListener('click', function(){
         fetch('?action=draw')
         .then(response => response.json())
         .then(data => {
            if (data.error) {
                alert(data.error);
                // Opcional: si no quedan números, se puede desactivar el botón de "Sacar Número"
                document.getElementById('drawButton').disabled = true;
            } else {
                const number = data.number;
                // Marcar en el tablero general el número extraído
                document.getElementById('cell-' + number).classList.add('drawn');
                
                // Actualizar la lista de números extraídos
                const listDiv = document.getElementById('numbersList');
                listDiv.innerHTML = '';
                data.drawn.sort((a, b) => a - b);
                data.drawn.forEach(num => {
                   let span = document.createElement('span');
                   span.textContent = num;
                   listDiv.appendChild(span);
                });
                
                // Actualizar el marcado en el cartón
                updateCardMarking(data.drawn);
            }
         })
         .catch(error => console.error('Error al extraer el número:', error));
    });

    // Evento para reiniciar el juego
    document.getElementById('resetButton').addEventListener('click', function(){
        fetch('?action=reset')
        .then(response => response.json())
        .then(data => {
            if(data.status === 'reset'){
                // Reactivar el botón de extraer número si estaba deshabilitado
                document.getElementById('drawButton').disabled = false;
                // Limpiar el listado de números extraídos
                document.getElementById('numbersList').innerHTML = '';
                // Reiniciar el tablero general: quitar la clase "drawn" de todas las celdas
                for (let i = 1; i <= 90; i++){
                    document.getElementById('cell-' + i).classList.remove('drawn');
                }
            }
        })
        .catch(error => console.error('Error al reiniciar el juego:', error));
    });
</script>
</body>
</html>
