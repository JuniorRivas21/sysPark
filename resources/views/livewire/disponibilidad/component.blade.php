<div class="card">
    <div class="card-header text-center" style="background-color: #f8f9fa;">
        <h4 style="font-weight: bold; color: #4a4a4a;">Disponibilidad</h4>
    </div>
    <div class="card-body">
        <p>Carro:
            @if ($disponibilidad['carro'] == 0)
            lleno
            @else
            {{ $disponibilidad['carro'] }} libres
            @endif
        </p>
        <p>Moto:
            @if ($disponibilidad['moto'] == 0)
            lleno
            @else
            {{ $disponibilidad['moto'] }} libres
            @endif
        </p>
        <p>Scooter eléctrico:
            @if ($disponibilidad['scooter'] == 0)
            lleno
            @else
            {{ $disponibilidad['scooter'] }} libres
            @endif
        </p>
        <p>Bicicleta:
            @if ($disponibilidad['bicicleta'] == 0)
            lleno
            @else
            {{ $disponibilidad['bicicleta'] }} libres
            @endif
        </p>
    </div>
</div>
