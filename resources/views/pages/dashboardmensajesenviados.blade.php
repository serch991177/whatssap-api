@extends('layouts.app', ['page' => __('Estados Whatsapp Mensajes Reporte'), 'pageSlug' => 'Estados Whatsapp Mensajes Reporte'])
@section('content')
<div class="row">
  {{-- KPI Cards --}}
  <div class="col-md-3">
    <div class="card text-white bg-primary shadow rounded-lg">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6>Total Mensajes</h6>
          <h3>{{ $totalMessages }}</h3>
        </div>
        <i class="fa fa-comments fa-2x"></i>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-success shadow rounded-lg">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6>Mensajes Hoy</h6>
          <h3>{{ $todayMessages }}</h3>
        </div>
        <i class="fa fa-calendar-day fa-2x"></i>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning shadow rounded-lg">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6>Tipos de Mensaje</h6>
          <h3>{{ $uniqueTypes }}</h3>
        </div>
        <i class="fa fa-tags fa-2x"></i>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-danger shadow rounded-lg">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6>Top Teléfono</h6>
          <h5>{{ $topPhone->phone ?? '-' }}</h5>
          <small>{{ $topPhone->total ?? 0 }} mensajes</small>
        </div>
        <i class="fa fa-user fa-2x"></i>
      </div>
    </div>
  </div>
</div>

{{-- Gráficos --}}
<div class="row mt-4">
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="title">Evolución de Mensajes</h5>
      </div>
      <div class="card-body">
        <canvas id="chartMessagesEvolution"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="title">Distribución por Tipo</h5>
      </div>
      <div class="card-body">
        <canvas id="chartTypeDistribution"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Tabla top teléfonos --}}
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="title">Top 5 Teléfonos Activos</h5>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Teléfono</th>
              <th>Total Mensajes</th>
            </tr>
          </thead>
          <tbody>
            @foreach($topPhones as $phone => $total)
              <tr>
                <td>{{ $phone }}</td>
                <td>{{ $total }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const messagesEvolution = @json($messagesByDay);
  const typeDistribution = @json($messagesByType);

  // Evolución
  new Chart(document.getElementById('chartMessagesEvolution'), {
    type: 'line',
    data: {
      labels: Object.keys(messagesEvolution),
      datasets: [{
        label: 'Mensajes',
        data: Object.values(messagesEvolution),
        borderColor: '#007bff',
        backgroundColor: 'rgba(0, 123, 255, 0.3)',
        fill: true,
        tension: 0.3
      }]
    }
  });

  // Distribución por tipo
  new Chart(document.getElementById('chartTypeDistribution'), {
    type: 'pie',
    data: {
      labels: Object.keys(typeDistribution),
      datasets: [{
        data: Object.values(typeDistribution),
        backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8']
      }]
    }
  });
</script>
@endsection