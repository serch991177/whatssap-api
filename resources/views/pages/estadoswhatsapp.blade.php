@extends('layouts.app', ['page' => __('Whatsapp State Messages'), 'pageSlug' => 'Whatsapp State Messages'])
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="title">Mensajes Enviados con estados</h5>
      </div>
      <div class="card-body all-icons">
        <div class="row">
          <div class="col-12">
            <table id="tablemessages" class="data-table dataTable no-footer dtr-inline collapsed">
              <thead>
                <tr>
                  <th>Fecha Envio</th>
                  <th>Fecha de Actualizacion</th>
                  <th>Teléfono</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($estados as $estado)
                  <tr>
                    <td>{{ $estado->created_at }}</td>
                    <td>{{ $estado->estado_at }}</td>
                    <td>{{ $estado->phone }}</td>
                    <td>
                      @switch($estado->ack)
                          @case(0)
                              <span class="badge bg-secondary">⏳ Pendiente</span>
                              @break
                          @case(1)
                              <span class="badge bg-info">📤 Enviado</span>
                              @break
                          @case(2)
                              <span class="badge bg-primary">📲 Entregado</span>
                              @break
                          @case(3)
                              <span class="badge bg-success">✅ Leído</span>
                              @break
                          @default
                              <span class="badge bg-dark">❔ Desconocido</span>
                      @endswitch
                    </td>
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
<div class="row mt-4">
  <!-- Global: Estado de mensajes -->
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header"><h6>📊 Estados Globales</h6></div>
      <div class="card-body">
        <canvas id="chartEstados"></canvas>
      </div>
    </div>
  </div>
  <!-- Global: Mensajes por día -->
  <div class="col-md-6">
    <div class="card shadow">
      <div class="card-header"><h6>📅 Mensajes por Día</h6></div>
      <div class="card-body">
        <canvas id="chartPorDia"></canvas>
      </div>
    </div>
  </div>
</div>
<div class="row mt-4">
  <!-- Individual: Top 5 números -->
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header"><h6>☎️ Top 5 Números con más mensajes</h6></div>
      <div class="card-body">
        <canvas id="chartTopNumeros"></canvas>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // 📊 Estados globales
  const ctxEstados = document.getElementById('chartEstados').getContext('2d');
  new Chart(ctxEstados, {
    type: 'doughnut',
    data: {
      labels: ['Pendientes','Enviados','Entregados','Leídos'],
      datasets: [{
        data: [
          {{ $estadisticas['pendientes'] }},
          {{ $estadisticas['enviados'] }},
          {{ $estadisticas['entregados'] }},
          {{ $estadisticas['leidos'] }}
        ],
        backgroundColor: ['#6c757d','#0dcaf0','#0d6efd','#198754']
      }]
    }
  });

  // 📅 Mensajes por día
  const ctxPorDia = document.getElementById('chartPorDia').getContext('2d');
  new Chart(ctxPorDia, {
    type: 'line',
    data: {
      labels: {!! json_encode($porDia->keys()) !!},
      datasets: [{
        label: 'Cantidad de mensajes',
        data: {!! json_encode($porDia->values()) !!},
        fill: true,
        borderColor: '#0d6efd',
        tension: 0.3
      }]
    }
  });

  // ☎️ Top números
  const ctxTop = document.getElementById('chartTopNumeros').getContext('2d');
  new Chart(ctxTop, {
    type: 'bar',
    data: {
      labels: {!! json_encode($topNumeros->keys()) !!},
      datasets: [{
        label: 'Mensajes',
        data: {!! json_encode($topNumeros->values()) !!},
        backgroundColor: '#6610f2'
      }]
    }
  });
</script>

<script>
  $('#tablemessages').DataTable( {
    responsive: true,
    paging: true,        
    searching: true,   
    ordering: true,     
    lengthChange: true   
  });
</script>
@endsection