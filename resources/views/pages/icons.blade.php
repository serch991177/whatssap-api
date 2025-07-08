@extends('layouts.app', ['page' => __('Icons'), 'pageSlug' => 'icons'])

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="title">100 Awesome Nucleo Icons</h5>
          <p class="category">Handcrafted by our friends from
            <a href="https://nucleoapp.com/?ref=1712">NucleoApp</a>
          </p>
        </div>
        <div class="card-body all-icons">
          <div class="row">
            <!-- resources/views/reportes.blade.php -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Teléfono</th>
                        <th>Mensaje</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->phone }}</td>
                            <td>{{ Str::limit($log->message, 50) }}</td>
                            <td>{{ $log->type }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
