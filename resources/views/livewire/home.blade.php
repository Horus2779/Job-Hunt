<div>

    <div>
        <label for="country" class="fw-bold">País de Busca</label>
        <select id="country" class="form-control" wire:model.live="country">
            <option value="br">Brasil</option>
            <option value="ca">Canadá</option>
            <option value="gb">Grã-Bretanha</option>
            <option value="ie">Irlarda (Jooble)</option>
        </select>

        <div wire:loading wire:target="country" class="text-primary mt-2">
            <span class="spinner-border spinner-border-sm"></span> Atualizando Vagas
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6 col-sm-12">

            <div class="container mt-4 bg-secondary-subtle rounded panel">
                <div class="p-2 m-1">
                    <img src="//zunastatic-abf.kxcdn.com/images/global/adzuna_logo.svg" alt="adzuna" class="img-fluid img-logo">
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
            <div class="container mt-4 bg-secondary-subtle rounded panel">
                <div class="p-2 m-1">
                    <img src="https://br.jooble.org/assets/images/favicon/ua/icon.svg?v=4" alt="jooble" class="img-fluid img-logo">
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
            --bs-pagination-active-bg: #6c757d;
            --bs-pagination-active-border-color: #6c757d;
            --bs-pagination-color: #6c757d;
            --bs-pagination-hover-color: #495057;
        }

        .panel {
            min-height: 865px;
        }

        .img-logo {
            max-height: 45px;
        }
    </style>
</div>
