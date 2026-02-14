<div>
    <div class="row">
        <div class="col-lg-6 col-sm-12">

            <div class="container mt-4 bg-secondary-subtle rounded">
                <div class="p-2 m-1">
                    <img src="//zunastatic-abf.kxcdn.com/images/global/adzuna_logo.svg" alt="adzuna" class="img-fluid">
                </div>
                @foreach ($adzuna as $job)
                    <x-job-card :job="$job" />
                @endforeach

                @if(empty($adzuna))
                    <div class="alert alert-info">Nenhuma vaga encontrada.</div>
                @endif

                <div class="mt-4 d-flex justify-content-center">
                    {{ $adzuna->links(data: ['pageName' => 'adzuna']) }}
                </div>

            </div>

        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="container mt-4 bg-secondary-subtle rounded">
                <div class="p-2 m-1">
                    <img src="https://br.jooble.org/assets/images/favicon/ua/icon.svg?v=4" alt="jooble" class="img-fluid">
                </div>
                @foreach ($jooble as $job)
                    <x-job-card :job="$job" />
                @endforeach

                @if(empty($adzuna))
                    <div class="alert alert-info">Nenhuma vaga encontrada.</div>
                @endif

                <div class="mt-4 d-flex justify-content-center">
                    {{ $jooble->links(data: ['pageName' => 'jooble']) }}
                </div>

            </div>
        </div>
    </div>

    <style>
        .pagination {
            --bs-pagination-active-bg: #6c757d;    /* Fundo do item ativo */
            --bs-pagination-active-border-color: #6c757d; /* Borda do item ativo */
            --bs-pagination-color: #6c757d;        /* Cor do texto dos links */
            --bs-pagination-hover-color: #495057;  /* Cor do texto no hover */
        }
    </style>
</div>
