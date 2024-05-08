<div>
    <x-admin.section.card>
        <div class="d-flex justify-content-between">
            <div class="d-flex justify-content-between">
                <x-livewire.input wire:model="deposit" label="Deposit" />
                <div>
                    <button class="btn btn-primary" wire:click="setDeposit">Set Deposit</button>
                </div>
            </div>
            <div>
                <button onclick="openModal('admin.system.installments.form')" class="btn btn-success">{{ Icon::create() }} Create New</button>
            </div>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col" class="fw-bold">Days Before</th>
                <th scope="col" class="fw-bold">Percentage</th>
                <th scope="col" class="fw-bold">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($deposit))
                <tr>
                    <td>Deposit</td>
                    <td>{{$deposit}}%</td>
                    <td></td>
                </tr>
            @endif
            @php $total = $deposit; @endphp
            @foreach(array_reverse(\Settings::getDefaultInstallments(), true) as $days => $percentage)
                @php $total += $percentage; @endphp
                <tr>
                    <td>{{$days}} Days Before</td>
                    <td>{{$percentage}}%</td>
                    <td>
                        <button title="Edit" onclick="openModal('admin.system.installments.form', {days: {{$days}}, percentage: {{$percentage}}})" class="btn btn-outline-success btn-sm mb-1">
                            {{ Icon::edit() }}
                        </button>
                        <button wire:click="delete({{ $days }})" class="btn btn-outline-danger btn-sm mb-1" title="Delete">
                            {{ Icon::delete() }}
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="d-flex float-end">
            <h4 class="fw-bold">Total Percentage: {{ $total }}%</h4>
        </div>
    </x-admin.section.card>
</div>