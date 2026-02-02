<div>

    <form wire:submit.prevent="update" class="mb-3 bg-white p-3 rounded border border-dark">
        <div class="row g-3 justify-content-center align-items-end">
            <div class="col-12 text-center">
                <p class="fs-6 fw-bold">Add new Parameter</p>
            </div>
            <div class="col-4">
                <label for="parameter">Name Parameter</label>
                <input type="text" wire:model="parameter" id="parameter" class="form-control">
                @error('parameter') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-2">
                <label for="logic">Type Logic</label>
                <select wire:model="logic" id="logic" class="form-select">
                    <option value=""></option>
                    <option value="AND">E</option>
                    <option value="OR">OU</option>
                </select>
            </div>
            <div class="col-2 d-flex justify-content-center align-items-bottom pt-3">
                <input type="submit" value="Add" id="add_button" class="btn btn-secondary">
            </div>
        </div>
    </form>

    <div class="row g-0 text-center justify-content-center align-items-center mb-2">
        <div class="col-4 border-bottom">
            <p class="fs-6 fw-bold m-0 p-2">Parameter</p>
        </div>
        <div class="col-4 border-bottom">
            <p class="fs-6 fw-bold m-0 p-2">Logic Operator</p>
        </div>
    </div>
    <div class="p-3">
    @if (count($parameters) > 0)
        <div class="row justify-content-center">
            <div class="col-8"> 
                
                @foreach ($parameters as $parameter)
                    <div class="row g-0 bg-white text-center justify-content-center align-items-center border rounded border-secondary mb-1 mx-0">
                        <div class="col-6">
                            <p class="m-0 p-2">{{ $parameter->parameter }}</p>
                        </div>
                        <div class="col-4 border-start">
                            <p class="m-0 p-2">{{ $parameter->logic_operator }}</p>
                        </div>
                        <div class="col-2">
                            <input type="button" value="X" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $parameter->id }})" wire:confirm="Are You sure you want do delete ?">
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    @endif
</div>
    
    
</div>
