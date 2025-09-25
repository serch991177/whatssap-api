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