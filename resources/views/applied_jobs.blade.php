@extends('layouts.admin')


@section('content')

<div class="mb-4">
    <p class="fw-bold fs-4">Vagas Inscritas</h4>
</div>
<hr>
<div>

    <div class="col-12 row">
        <span class="fs-5 col-3">Titulo</span>
        <span class="fs-5 col-3">Empresa</span>
        <span class="fs-5 col-3">Localização</span>
        <span class="fs-5 col-3">Fonte</span>
    </div>

    @foreach ($jobs as $job)
        <div class="bg-white row p-2 m-2 rounded">
            <span class="fs-6 col-3">{{ $job->title }}</span>
            <span class="fs-6 col-3">{{ $job->company }}</span>
            <span class="fs-6 col-3">{{ $job->location }}</span>
            <span class="fs-6 col-2">{{ $job->source_id }}</span>
            <span class="fs-6 col-1"><a href="{{ $job->url }}" target="_blank" class="btn btn-secondary">Vaga</a></span>
        </div>
    @endforeach
</div>
@endsection