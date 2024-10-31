<div class="container mt-5">
    <div class="row">
        <!-- Columna para el formulario -->
        <div class="col-md-4">
            <div class="card p-4">
                <h2 class="text-center" style="font-size: 20px; font-weight: bold; color: #007bff;">
                    Filtrar Ingresos
                </h2>

                <!-- Control de filtrado por fecha -->
                <div class="mb-3">
                    <label for="dateInput" class="form-label">Fecha:</label>
                    <input type="date" id="dateInput" class="form-control" onchange="fetchData()">
                </div>

                <!-- Control de filtrado por tipo de vehículo -->
                <div class="mb-3">
                    <label for="vehicleTypeSelect" class="form-label">Tipo de vehículo:</label>
                    <select id="vehicleTypeSelect" class="form-select" onchange="filterData()">
                        <option value="all">Todos</option>
                        <option value="carro">carro</option>
                        <option value="bicicleta">bicicleta</option>
                        <option value="scooter electrico">scooter electrico</option>
                        <option value="moto">moto</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Columna para el gráfico -->
        <div class="col-md-8">
            <div class="card p-4">
                <h2 class="text-center" style="font-size: 20px; font-weight: bold; color: #007bff;">
                    Ingreso de Vehículos por Hora
                </h2>
                <div id="chart_div" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Script de Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Cargar la biblioteca de Google Charts y llamar a fetchData cuando esté lista
    google.charts.load('current', {
        'packages': ['corechart','line']
    });
    google.charts.setOnLoadCallback(setDateAndFetchData);

    let originalData = []; // Inicializar como un array vacío para almacenar los datos reales

    // Función para establecer la fecha actual y obtener datos reales desde el backend
    function setDateAndFetchData() {
        // Establecer la fecha actual en el input de fecha
        const dateInput = document.getElementById('dateInput');
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;

        // Llamar a fetchData para obtener los datos con la fecha actual
        fetchData();
    }

    // Función para obtener datos reales desde el backend
    function fetchData() {
        // Obtener el valor de la fecha
        const date = document.getElementById('dateInput').value;

        // Construir la URL con el parámetro de fecha
        const url = `/obtener-datos?fecha=${date}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log("Datos obtenidos del servidor:", data);
                if (data.message) {
                document.getElementById('chart_div').innerHTML = "<p style='color: red; text-align: center;'>" + data.message + "</p>";
                return; // Salir de la función
            }
                originalData = data;
                drawVisualization();
            })
            .catch(error => console.error('Error al obtener los datos:', error));
    }

    // Función para dibujar el gráfico
    function drawVisualization(filteredData = null) {
        try {
            const dataToDraw = filteredData || originalData;
            if (dataToDraw.length <= 1 || dataToDraw.every(row => row.slice(1).every(value => isNaN(value)))) {
                document.getElementById('chart_div').innerHTML = "<p style='color: red; text-align: center;'>No se encuentra información para mostrar en el gráfico.</p>";
                return;
            }

            const data = google.visualization.arrayToDataTable(dataToDraw);

            const options = {
                title: 'Número de Vehículos por Hora',
                vAxis: {
                    title: 'Cantidad de Vehículos',
                    viewWindow: { min: 0, max: 10 },
                    ticks: [0, 2, 4, 6, 8, 10],
                    baselineColor: '#FF0000',
                    baseline: 7
                },
                hAxis: { title: 'Hora' },
                colors: ['#4285F4', '#DB4437', 'Yellow', 'Green'],
                legend: { position: 'bottom' },
                lineWidth: 3
            };

            const chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        } catch (error) {
            console.error('Error al dibujar el gráfico:', error);
            document.getElementById('chart_div').innerHTML = "<p style='color: red; text-align: center;'>No se encuentra información para mostrar en el gráfico.</p>";
        }
    }

    // Función para filtrar datos por tipo de vehículo
    function filterData() {
        const vehicleType = document.getElementById('vehicleTypeSelect').value;

        let filteredData = originalData.map(row => row.slice());

        if (vehicleType !== 'all') {
            const vehicleTypeIndex = {
                'carro': 1,
                'bicicleta': 2,
                'scooter electrico': 3,
                'moto': 4
            };

            filteredData = filteredData.map(row => {
                if (row[0] === 'Hora') return row;
                return row.map((value, index) => {
                    if (index === vehicleTypeIndex[vehicleType] || index === 0) return value;
                    return 0;
                });
            });
        }

        drawVisualization(filteredData);
    }
</script>
