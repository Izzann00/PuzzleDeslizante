<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Puzzle Deslizante</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                min-height: 100vh;
                margin: 0;
                background: #0b1020;
                color: white;
                font-family: Arial, sans-serif;
            }

            .contenedor {
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 30px 15px;
            }

            .panel {
                width: 100%;
                max-width: 560px;
                padding: 35px;
                background: #151c33;
                border: 2px solid #27345c;
                border-radius: 20px;
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
                text-align: center;
            }

            .titulo {
                margin-bottom: 10px;
                font-size: 42px;
                font-weight: bold;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: #4dabf7;
                text-shadow: 0 0 12px rgba(77, 171, 247, 0.7);
            }

            .subtitulo {
                margin-bottom: 25px;
                color: #8993ad;
            }

            .selector-tamaño {
                margin-bottom: 25px;
                text-align: left;
            }

            .selector-tamaño label {
                display: block;
                margin-bottom: 8px;
                color: #8993ad;
                font-weight: bold;
            }

            .selector-tamaño select {
                background-color: #0b1020;
                color: white;
                border: 1px solid #27345c;
                padding: 10px;
            }

            .selector-tamaño select:focus {
                background-color: #0b1020;
                color: white;
                border-color: #4dabf7;
                box-shadow: 0 0 8px rgba(77, 171, 247, 0.4);
            }

            #puzzle {
                --columnas: 4;
                --imagen-tamaño: 400%;

                width: 100%;
                max-width: 420px;
                aspect-ratio: 1 / 1;
                margin: 0 auto;

                display: grid;
                grid-template-columns: repeat(var(--columnas), minmax(0, 1fr));
                grid-template-rows: repeat(var(--columnas), minmax(0, 1fr));
                gap: 5px;

                position: relative;
                overflow: hidden;
            }

            #puzzle::before {
                content: "";
                position: absolute;
                inset: 0;

                background-image: url('{{ asset('images/pajaro.jpg') }}');
                background-size: 100% 100%;
                background-position: center;
                background-repeat: no-repeat;

                filter: blur(2px);
                opacity: 0.45;

                z-index: 0;
            }

            .pieza {
                width: 100%;
                height: 100%;
                min-width: 0;
                min-height: 0;

                position: relative;
                z-index: 1;

                background-image: url('{{ asset('images/pajaro.jpg') }}');
                background-size: var(--imagen-tamaño) var(--imagen-tamaño);
                background-repeat: no-repeat;

                border: 2px solid #42a5f5;
                border-radius: 8px;

                cursor: pointer;
                user-select: none;

                box-shadow:
                    0 5px 10px rgba(0, 0, 0, 0.4),
                    inset 0 0 10px rgba(255, 255, 255, 0.15);
            }

            .pieza:hover {
                box-shadow:
                    0 8px 15px rgba(0, 0, 0, 0.5),
                    0 0 15px rgba(33, 150, 243, 0.5);
            }

            .vacia {
                background-image: none;
                background-color: rgba(11, 16, 32, 0.35);
                border: 2px dashed #27345c;
                box-shadow: none;
                cursor: default;
            }

            .vacia:hover {
                box-shadow: none;
            }

            .botones {
                margin-top: 30px;
            }

            .boton-juego {
                padding: 10px 25px;
                font-size: 16px;
                font-weight: bold;
                border-radius: 8px;
                transition: 0.15s;
            }

            .boton-juego:hover {
                transform: translateY(-2px);
            }

            .contador {
                margin-top: 25px;
                padding: 10px 20px;
                display: inline-block;
                background: #0b1020;
                border: 1px solid #27345c;
                border-radius: 8px;
                color: #8993ad;
            }

            .contador strong {
                color: #4dabf7;
                font-size: 20px;
            }

            #victoria {
                position: fixed;
                inset: 0;
                display: none;
                justify-content: center;
                align-items: center;
                padding: 15px;
                background: rgba(5, 8, 20, 0.85);
                z-index: 1000;
            }

            .ventana-victoria {
                width: 90%;
                max-width: 450px;
                padding: 40px;
                text-align: center;
                background: #151c33;
                border: 2px solid #4dabf7;
                border-radius: 20px;
                box-shadow: 0 0 30px rgba(77, 171, 247, 0.5);
                animation: aparecer 0.3s ease;
            }

            .icono-victoria {
                font-size: 70px;
                margin-bottom: 15px;
            }

            .titulo-victoria {
                color: #4dabf7;
                font-size: 32px;
                font-weight: bold;
                text-transform: uppercase;
                margin-bottom: 15px;
            }

            .texto-victoria {
                color: #8993ad;
                font-size: 18px;
                margin-bottom: 25px;
            }

            .resultado-movimientos {
                color: white;
                font-size: 20px;
                margin-bottom: 25px;
            }

            .resultado-movimientos strong {
                color: #4dabf7;
            }

        </style>
    </head>

    <body>

        <div class="contenedor">
            <div class="panel">

                <h1 class="titulo">Puzzle</h1>

                <p class="subtitulo">
                    Ordena las piezas y completa el desafío
                </p>

                <div class="selector-tamaño">
                    <label for="tamaño">Tamaño del puzzle</label>

                    <select id="tamaño" class="form-select">
                        <option value="3">3 × 3</option>
                        <option value="4" selected>4 × 4</option>
                    </select>
                </div>

                <div id="puzzle"></div>

                <div class="botones">
                    <button id="mezclar" class="btn btn-primary boton-juego">
                        Mezclar
                    </button>
                </div>

                <div class="contador">
                    Movimientos:
                    <strong id="movimientos">0</strong>
                </div>

            </div>
        </div>

        <div id="victoria">
            <div class="ventana-victoria">

                <div class="icono-victoria">🏆</div>

                <div class="titulo-victoria">
                    ¡Puzzle completado!
                </div>

                <div class="texto-victoria">
                    ¡Enhorabuena! Has conseguido ordenar todas las piezas.
                </div>

                <div class="resultado-movimientos">
                    Movimientos:
                    <strong id="resultadoMovimientos">0</strong>
                </div>

                <button id="jugarDeNuevo" class="btn btn-primary boton-juego">
                    Jugar de nuevo
                </button>

            </div>
        </div>

        <script>

            const puzzle = document.getElementById('puzzle');
            const selectortamaño = document.getElementById('tamaño');
            const botonMezclar = document.getElementById('mezclar');
            const contadorMovimientos = document.getElementById('movimientos');
            const pantallaVictoria = document.getElementById('victoria');
            const resultadoMovimientos = document.getElementById('resultadoMovimientos');
            const jugarDeNuevo = document.getElementById('jugarDeNuevo');

            let tamaño = 4;
            let tablero = [];
            let movimientos = 0;
            let piezas = [];
            let partidaIniciada = false;

            function crearTablero() {
                const total = tamaño * tamaño;

                tablero = [];

                for (let i = 1; i < total; i++) {
                    tablero.push(i);
                }

                tablero.push(0);

                movimientos = 0;
                partidaIniciada = false;

                contadorMovimientos.textContent = movimientos;
                pantallaVictoria.style.display = 'none';

                puzzle.style.setProperty('--columnas', tamaño);
                puzzle.style.setProperty( '--imagen-tamaño', (tamaño * 100) + '%' );

                puzzle.innerHTML = '';

                for (let i = 0; i < total; i++) {
                    const pieza = document.createElement('div');

                    pieza.classList.add('pieza');

                    pieza.addEventListener('click', () => {
                        moverPieza(i);
                    });

                    puzzle.appendChild(pieza);
                }

                piezas = puzzle.querySelectorAll('.pieza');

                mostrarTablero();
            }

            function mostrarTablero() {
                piezas.forEach((pieza, index) => {
                    const numeroPieza = tablero[index];

                    pieza.classList.remove('vacia');

                    if (numeroPieza === 0) {
                        pieza.classList.add('vacia');
                        pieza.style.backgroundPosition = '';
                        return;
                    }

                    const fila = Math.floor((numeroPieza - 1) / tamaño);
                    const columna = (numeroPieza - 1) % tamaño;

                    const posicionX = tamaño === 1 ? 0 : (columna / (tamaño - 1)) * 100;

                    const posicionY = tamaño === 1 ? 0 : (fila / (tamaño - 1)) * 100;

                    pieza.style.backgroundPosition = posicionX + '% ' + posicionY + '%';
                });
            }

            function puedeMover(posicion) {
                const posicionVacia = tablero.indexOf(0);

                const filaPieza = Math.floor(posicion / tamaño);
                const columnaPieza = posicion % tamaño;

                const filaVacia = Math.floor(posicionVacia / tamaño);
                const columnaVacia = posicionVacia % tamaño;

                const mismaFila = filaPieza === filaVacia;
                const mismaColumna = columnaPieza === columnaVacia;

                const cercaHorizontal = mismaFila && Math.abs(columnaPieza - columnaVacia) === 1;

                const cercaVertical = mismaColumna && Math.abs(filaPieza - filaVacia) === 1;

                return cercaHorizontal || cercaVertical;
            }

            function moverPieza(posicion) {
                if (!partidaIniciada || !puedeMover(posicion)) {
                    return;
                }

                const posicionVacia = tablero.indexOf(0);

                tablero[posicionVacia] = tablero[posicion];
                tablero[posicion] = 0;

                movimientos++;

                contadorMovimientos.textContent = movimientos;

                mostrarTablero();
                comprobarVictoria();
            }

            function mezclarPuzzle() {
                pantallaVictoria.style.display = 'none';

                movimientos = 0;
                contadorMovimientos.textContent = movimientos;

                tablero = [];

                for (let i = 1; i < tamaño * tamaño; i++) {
                    tablero.push(i);
                }

                tablero.push(0);

                let movimientoAnterior = -1;

                for (let i = 0; i < 200; i++) {
                    const posicionVacia = tablero.indexOf(0);
                    const movimientosPosibles = [];

                    for (let posicion = 0; posicion < tablero.length; posicion++) {
                        if (
                            puedeMover(posicion) &&
                            posicion !== movimientoAnterior
                        ) {
                            movimientosPosibles.push(posicion);
                        }
                    }

                    if (movimientosPosibles.length === 0) {
                        for (let posicion = 0; posicion < tablero.length; posicion++) {
                            if (puedeMover(posicion)) {
                                movimientosPosibles.push(posicion);
                            }
                        }
                    }

                    const posicionAleatoria = movimientosPosibles[ Math.floor(Math.random() * movimientosPosibles.length) ];

                    tablero[posicionVacia] = tablero[posicionAleatoria];
                    tablero[posicionAleatoria] = 0;

                    movimientoAnterior = posicionVacia;
                }

                partidaIniciada = true;
                mostrarTablero();
            }

            selectortamaño.addEventListener('change', () => {
                tamaño = Number(selectortamaño.value);
                crearTablero();
            });

            botonMezclar.addEventListener('click', () => {
                mezclarPuzzle();
            });

            jugarDeNuevo.addEventListener('click', () => {
                mezclarPuzzle();
            });

            function comprobarVictoria() {
                const tableroCorrecto = [];

                for (let i = 1; i < tamaño * tamaño; i++) {
                    tableroCorrecto.push(i);
                }

                tableroCorrecto.push(0);

                const completado = tablero.every( (valor, index) => valor === tableroCorrecto[index] );

                if (completado && partidaIniciada) {
                    partidaIniciada = false;

                    resultadoMovimientos.textContent = movimientos;
                    pantallaVictoria.style.display = 'flex';
                }
            }

            crearTablero();

        </script>

    </body>
</html>
