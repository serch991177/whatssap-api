@extends('layouts.app', ['page' => __('Estados Whatsapp Reporte'), 'pageSlug' => 'Estados Whatsapp Reporte'])
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="mb-0">📊 Reporte Global de Estados</h4>
      </div>
      <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                <div class="card-body">Pendientes: {{ $estados->where('ack',0)->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                <div class="card-body">Enviados: {{ $estados->where('ack',1)->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                <div class="card-body">Entregados: {{ $estados->where('ack',2)->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                <div class="card-body">Leídos: {{ $estados->where('ack',3)->count() }}</div>
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-3">
                <canvas id="globalChart"></canvas>
            </div>
            <div class="col-md-3">
                <canvas id="individualChart"></canvas>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('globalChart').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Pendiente', 'Enviado', 'Entregado', 'Leído'],
      datasets: [{
        data: [
          {{ $estados->where('ack', 0)->count() }},
          {{ $estados->where('ack', 1)->count() }},
          {{ $estados->where('ack', 2)->count() }},
          {{ $estados->where('ack', 3)->count() }}
        ],
        backgroundColor: ['#6c757d', '#0dcaf0', '#0d6efd', '#198754']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
</script>
<script>
  const ctx2 = document.getElementById('individualChart').getContext('2d');
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: [
        @foreach($estados->where('phone', $telefonoSeleccionado ?? '') as $estado)
          "{{ $estado->created_at->format('d/m H:i') }}",
        @endforeach
      ],
      datasets: [{
        label: 'Estados',
        data: [
          @foreach($estados->where('phone', $telefonoSeleccionado ?? '') as $estado)
            {{ $estado->ack }},
          @endforeach
        ],
        backgroundColor: '#6610f2'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          ticks: {
            callback: function(value) {
              const estados = ['Pendiente','Enviado','Entregado','Leído'];
              return estados[value] ?? value;
            }
          },
          stepSize: 1,
          min: 0,
          max: 3
        }
      }
    }
  });
</script>
@endsection
