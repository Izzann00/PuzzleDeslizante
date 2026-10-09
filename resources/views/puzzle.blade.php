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
                margin-bottom: 30px;
                color: #8993ad;
            }

            #puzzle {
                width: 420px;
                height: 420px;
                margin: 0 auto;

                display: grid;
                grid-template-columns: repeat(4, 100px);
                grid-template-rows: repeat(4, 100px);
                gap: 5px;

                position: relative;
                overflow: hidden;
            }

            #puzzle::before {
                content: "";

                position: absolute;
                top: 0;
                left: 0;

                width: 100%;
                height: 100%;

                background-image: url('{{ asset('images/pajaro.jpg') }}');
                background-size: 400px 400px;
                background-position: center;
                background-repeat: no-repeat;

                filter: blur(2px);
                opacity: 0.45;

                z-index: 0;
            }

            .pieza {
                width: 100px;
                height: 100px;

                display: flex;
                justify-content: center;
                align-items: center;

                position: relative;
                z-index: 1;

                background-image: url('{{ asset('images/pajaro.jpg') }}');
                background-size: 400px 400px;
                background-repeat: no-repeat;

                color: white;
                font-size: 30px;
                font-weight: bold;

                border: 2px solid #42a5f5;
                border-radius: 10px;

                cursor: pointer;
                user-select: none;

                box-shadow:
                    0 5px 10px rgba(0, 0, 0, 0.4),
                    inset 0 0 10px rgba(255, 255, 255, 0.15);
            }

            .pieza-1 {
                background-position: 0 0;
            }

            .pieza-2 {
                background-position: -100px 0;
            }

            .pieza-3 {
                background-position: -200px 0;
            }

            .pieza-4 {
                background-position: -300px 0;
            }

            .pieza-5 {
                background-position: 0 -100px;
            }

            .pieza-6 {
                background-position: -100px -100px;
            }

            .pieza-7 {
                background-position: -200px -100px;
            }

            .pieza-8 {
                background-position: -300px -100px;
            }

            .pieza-9 {
                background-position: 0 -200px;
            }

            .pieza-10 {
                background-position: -100px -200px;
            }

            .pieza-11 {
                background-position: -200px -200px;
            }

            .pieza-12 {
                background-position: -300px -200px;
            }

            .pieza-13 {
                background-position: 0 -300px;
            }

            .pieza-14 {
                background-position: -100px -300px;
            }

            .pieza-15 {
                background-position: -200px -300px;
            }

            .pieza:hover {
                box-shadow:
                    0 8px 15px rgba(0, 0, 0, 0.5),
                    0 0 15px rgba(33, 150, 243, 0.5);
            }

            .vacia {
                background: rgba(11, 16, 32, 0.35);
                border: 2px dashed #27345c;
                box-shadow: none;
                cursor: default;
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
                top: 0;
                left: 0;

                width: 100%;
                height: 100%;

                display: none;

                justify-content: center;
                align-items: center;

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

                box-shadow:
                    0 0 30px rgba(77, 171, 247, 0.5);

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

                <div id="puzzle">

                    <div class="pieza pieza-1"></div>
                    <div class="pieza pieza-2"></div>
                    <div class="pieza pieza-3"></div>
                    <div class="pieza pieza-4"></div>

                    <div class="pieza pieza-5"></div>
                    <div class="pieza pieza-6"></div>
                    <div class="pieza pieza-7"></div>
                    <div class="pieza pieza-8"></div>

                    <div class="pieza pieza-9"></div>
                    <div class="pieza pieza-10"></div>
                    <div class="pieza pieza-11"></div>
                    <div class="pieza pieza-12"></div>

                    <div class="pieza pieza-13"></div>
                    <div class="pieza pieza-14"></div>
                    <div class="pieza pieza-15"></div>
                    <div class="pieza vacia"></div>

                </div>

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

                <div class="icono-victoria"> 🏆 </div>

                <div class="titulo-victoria"> ¡Puzzle completado! </div>

                <div class="texto-victoria"> ¡Enhorabuena! Has conseguido ordenar todas las piezas. </div>

                <div class="resultado-movimientos"> 
                    Movimientos: 
                    <strong id="resultadoMovimientos">0</strong>
                </div>

                <button
                    id="jugarDeNuevo"
                    class="btn btn-primary boton-juego">
                    Jugar de nuevo
                </button>

            </div>
        </div>

        <script>

            const puzzle = document.getElementById('puzzle');

            const piezas = document.querySelectorAll('.pieza');

            const botonMezclar = document.getElementById('mezclar');

            const contadorMovimientos = document.getElementById('movimientos');

            const pantallaVictoria = document.getElementById('victoria');

            const resultadoMovimientos = document.getElementById('resultadoMovimientos');

            const jugarDeNuevo = document.getElementById('jugarDeNuevo');


            let tablero = [
                1, 2, 3, 4,
                5, 6, 7, 8,
                9, 10, 11, 12,
                13, 14, 15, 0
            ];

            let movimientos = 0;


            function mostrarTablero() {

                piezas.forEach((pieza, index) => {

                    const numeroPieza = tablero[index];

                    pieza.classList.remove(
                        'pieza-1',
                        'pieza-2',
                        'pieza-3',
                        'pieza-4',
                        'pieza-5',
                        'pieza-6',
                        'pieza-7',
                        'pieza-8',
                        'pieza-9',
                        'pieza-10',
                        'pieza-11',
                        'pieza-12',
                        'pieza-13',
                        'pieza-14',
                        'pieza-15',
                        'vacia'
                    );

                    if (numeroPieza === 0) {

                        pieza.classList.add('vacia');

                    } else {

                        pieza.classList.add(
                            'pieza-' + numeroPieza
                        );

                    }

                });
            }

            function puedeMover(posicion) {

                const posicionVacia = tablero.indexOf(0);

                const filaPieza = Math.floor(posicion / 4);

                const columnaPieza = posicion % 4;

                const filaVacia = Math.floor(posicionVacia / 4);

                const columnaVacia = posicionVacia % 4;

                const mismaFila = filaPieza === filaVacia;

                const mismaColumna = columnaPieza === columnaVacia;

                const cercaHorizontal = mismaFila && Math.abs( columnaPieza - columnaVacia ) === 1;

                const cercaVertical = mismaColumna && Math.abs( filaPieza - filaVacia ) === 1;

                return cercaHorizontal || cercaVertical;
            }


            function moverPieza(posicion) {

                if (!puedeMover(posicion)) {
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


            piezas.forEach((pieza, index) => {

                pieza.addEventListener('click', () => {

                    moverPieza(index);

                });

            });


            function mezclarPuzzle() {

                for (let i = 0; i < 200; i++) {

                    const posicionVacia = tablero.indexOf(0);

                    const movimientosPosibles = [];

                    for (
                        let posicion = 0;
                        posicion < 16;
                        posicion++
                    ) {

                        if (puedeMover(posicion)) {

                            movimientosPosibles.push(
                                posicion
                            );

                        }

                    }

                    const posicionAleatoria = movimientosPosibles[ Math.floor( Math.random() * movimientosPosibles.length ) ];

                    tablero[posicionVacia] = tablero[posicionAleatoria];

                    tablero[posicionAleatoria] = 0;
                }

                movimientos = 0;

                contadorMovimientos.textContent = movimientos;

                mostrarTablero();
            }

            botonMezclar.addEventListener('click', () => {

                mezclarPuzzle();

            });

            jugarDeNuevo.addEventListener('click', () => {

                pantallaVictoria.style.display = 'none';

                reiniciarPuzzle();

                mezclarPuzzle();

            });

            function reiniciarPuzzle() {

                tablero = [
                    1, 2, 3, 4,
                    5, 6, 7, 8,
                    9, 10, 11, 12,
                    13, 14, 15, 0
                ];

                movimientos = 0;

                contadorMovimientos.textContent = movimientos;

                mostrarTablero();
            }

            function comprobarVictoria() {

                const tableroCorrecto = [
                    1, 2, 3, 4,
                    5, 6, 7, 8,
                    9, 10, 11, 12,
                    13, 14, 15, 0
                ];

                if (
                    tablero.every( (valor, index) => valor === tableroCorrecto[index] )
                ) {
                    resultadoMovimientos.textContent = movimientos;

                    pantallaVictoria.style.display = 'flex';
                }
            }

            mezclarPuzzle();

        </script>

    </body>
</html>