
@props(['job'])

<div x-data="{ open: false }" class="card mb-2 shadow-sm">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-6">
                <h5 class="card-title mb-1 text-truncate" style="max-width: 250px;">
                    {{ $job['title'] }}
                </h5>
            </div>

            <div class="col-auto d-flex gap-2">

                <button
                    wire:click="applyToJob({{ json_encode($job) }})"
                    @click="window.open('{{ $job['url'] }}', '_blank')"
                    class="btn btn-secondary btn-sm px-3"
                >
                    Candidatar
                </button>
                
                <button @click="open = !open" type="button" class="btn btn-outline-secondary btn-sm">
                    <span x-text="open ? 'Recolher' : 'Ver detalhes'"></span>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-collapse x-cloak class="card-footer bg-light border-top-0">
        <div class="py-2">
            <p class="mb-1 small"><strong>Vaga:</strong> {{ $job['title'] }}</p>
            <p class="mb-1 small"><strong>Empresa:</strong> {{ $job['company'] }}</p>
            <p class="mb-1 small"><strong>Localização:</strong> {{ $job['location'] }}</p>
            <hr class="my-2 opacity-25">
            <p class="card-text small text-secondary">
                {{ $job['description'] }}
            </p>
        </div>
    </div>
</div>