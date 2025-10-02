@extends('layouts.app', ['page' => __('Whatsapp Incoming Messages'), 'pageSlug' => 'Whatsapp Incoming Messages'])
@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="title">Mensajes Respondidos</h5>
        </div>
        <div class="card-body all-icons">
          <div class="row">
            <div class="col-12">
                <table id="tablemessages" class="data-table dataTable no-footer dtr-inline collapsed">
                    <thead>
                        <tr>
                            <th>Fecha de mensaje</th>
                            <th>Número</th>
                            <th>Mensaje</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estados as $estado)
                            <tr>
                                <td>{{ $estado->received_at }}</td>
                                <td>{{ $estado->from }}</td>
                                <td> 
                                    <span title="{{ $estado->body }}">
                                        {{ Str::limit($estado->body, 50) }}
                                    </span>
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
{{-- DASHBOARD DE GRÁFICAS --}}
<div class="row mt-4">
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
      <div class="card-header"><h5>Top 5 Números</h5></div>
      <div class="card-body">
        <canvas id="chartTopSenders"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-12 mt-4">
    <div class="card">
      <div class="card-header"><h5>Mensajes por Hora</h5></div>
      <div class="card-body">
        <canvas id="chartMessagesByHour"></canvas>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Datos desde PHP
  const messagesByDay   = @json($messagesByDay);
  const topSenders      = @json($topSenders);
  const messagesByHour  = @json($messagesByHour);
  // Gráfico: mensajes por día
  new Chart(document.getElementById('chartMessagesByDay'), {
    type: 'line',
    data: {
      labels: Object.keys(messagesByDay),
      datasets: [{
        label: 'Mensajes',
        data: Object.values(messagesByDay),
        borderColor: '#007bff',
        backgroundColor: 'rgba(0, 123, 255, 0.3)',
        fill: true,
        tension: 0.3
      }]
    }
  });
  // Gráfico: top números
  new Chart(document.getElementById('chartTopSenders'), {
    type: 'bar',
    data: {
      labels: Object.keys(topSenders),
      datasets: [{
        label: 'Cantidad',
        data: Object.values(topSenders),
        backgroundColor: 'rgba(40, 167, 69, 0.6)'
      }]
    },
    options: {
      indexAxis: 'y'
    }
  });
  // Gráfico: mensajes por hora
  new Chart(document.getElementById('chartMessagesByHour'), {
    type: 'bar',
    data: {
      labels: Object.keys(messagesByHour).map(h => h + ":00"),
      datasets: [{
        label: 'Mensajes',
        data: Object.values(messagesByHour),
        backgroundColor: 'rgba(255, 193, 7, 0.6)'
      }]
    }
  });
  // DataTable
  $('#tablemessages').DataTable({
    responsive: true,
    paging: true,        
    searching: true,   
    ordering: true,     
    lengthChange: true   
  });
</script>
@endsection