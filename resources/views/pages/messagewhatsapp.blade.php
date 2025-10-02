@extends('layouts.app', ['page' => __('Whatsapp Messages'), 'pageSlug' => 'Whatsapp Messages'])
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="title">Mensajes Enviados</h5>
      </div>
      <div class="card-body all-icons">
        <div class="row">
          <div class="col-12">
            <table id="tablemessages" class="data-table dataTable no-footer dtr-inline collapsed">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Teléfono</th>
                  <th>Mensaje</th>
                  <th>Tipo</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($logs as $log)
                  <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->phone }}</td>
                    <td>{{ Str::limit($log->message, 50) }}</td>
                    <td>{{ $log->type }}</td>
                    <td></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- DASHBOARD DE GRÁFICAS --}}
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Mensajes por Día</h5></div>
      <div class="card-body">
        <canvas id="chartMessagesByDay"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Distribución por Tipo</h5></div>
      <div class="card-body">
        <canvas id="chartMessagesByType"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-12 mt-4">
    <div class="card">
      <div class="card-header"><h5>Top 5 Teléfonos</h5></div>
      <div class="card-body">
        <canvas id="chartTopPhones"></canvas>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  $('#tablemessages').DataTable( {
    responsive: true,
    paging: true,        
    searching: true,   
    ordering: true,     
    lengthChange: true   
  });
</script>
<script>
  // Datos de PHP -> JS
  const messagesByDay = @json($messagesByDay);
  const messagesByType = @json($messagesByType);
  const topPhones = @json($topPhones);
  // Gráfico: mensajes por día
  new Chart(document.getElementById('chartMessagesByDay'), {
    type: 'line',
    data: {
      labels: Object.keys(messagesByDay),
      datasets: [{
        label: 'Mensajes',
        data: Object.values(messagesByDay),
        borderColor: 'blue',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        fill: true,
        tension: 0.3
      }]
    }
  });
  // Gráfico: distribución por tipo
  new Chart(document.getElementById('chartMessagesByType'), {
    type: 'doughnut',
    data: {
      labels: Object.keys(messagesByType),
      datasets: [{
        data: Object.values(messagesByType),
        backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
      }]
    }
  });
  // Gráfico: top teléfonos
  new Chart(document.getElementById('chartTopPhones'), {
    type: 'bar',
    data: {
      labels: Object.keys(topPhones),
      datasets: [{
        label: 'Cantidad',
        data: Object.values(topPhones),
        backgroundColor: 'rgba(75, 192, 192, 0.5)'
      }]
    },
    options: {
      indexAxis: 'y'
    }
  });
</script>
@endsection