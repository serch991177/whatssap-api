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