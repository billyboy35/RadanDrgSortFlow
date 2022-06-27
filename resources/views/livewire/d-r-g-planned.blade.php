<div>
    @include('include.alert-result')
    <div class="card-body">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search fa-fw"></i></span>
            </div>
            <input type="text" class="form-control" wire:model="search" placeholder="Chercher une imbrication">
        </div>
    </div>
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th>#</th>
                <th>DRG nom </th>
                <th>Image</th>
                <th>Programme Progress</th>
                <th>Status</th>
                <th>
                    <a class="btn btn-secondary" wire:click.prevent="sortBy('material')" role="button" href="#">Matière @include('include.sort-icon', ['field' => 'material'])</a>
                </th>
                <th>
                    <a class="btn btn-secondary" wire:click.prevent="sortBy('thickness')" role="button" href="#">Epaisseur @include('include.sort-icon', ['field' => 'thickness'])</a>
                </th>
                <th></th>
                <th>Nombre de tôle</th>
                <th>Nombre de tôle coupée</th>
                <th>Temps unitaire</th>
                <th>Temps Total</th>
                <th>Temps Restant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTime = 0;
                $totalRemaningTime = 0;
            @endphp

            @forelse ($DRGList as $DRG)
            <tr>
                <td>#</td>
                <td>
                    <a>{{ $DRG->drg_name }}</a><br />
                    <small>{{ $DRG->GetPrettyCreatedAttribute() }}</small>
                </td>
                <td>
                    <img alt="Imbrication" src="{{ asset('/images/'. $DRG->drg_name .'.png') }}">
                </td>
                <td class="project_progress">
                    <div class="progress progress-sm">
                        @if($DRG->statu  != 7)
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}%">
                        @else
                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="{{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}%">
                        @endif
                            </div>
                    </div>
                    <small>
                        {{ round($DRG->sheet_qty_done/$DRG->sheet_qty*100,2) }}% Complete
                    </small>
                </td>
                <td class="project-state">
                    @if($DRG->statu  == 2)<span class="badge badge-warning">Planifier</span> @endif
                    @if($DRG->statu  == 3)<span class="badge badge-info">En cours</span> @endif
                    @if($DRG->statu  == 4)<span class="badge badge-danger">A refaire</span> @endif
                    @if($DRG->statu  == 7)<span class="badge badge-danger">Stopper</span> @endif
                    
                </td>
                <td>{{ $DRG->material }}</td>
                <td>{{ $DRG->thickness }}</td>
                <td>{{ $DRG->sheet_qty }}</td>
                <td>
                    @if($DRG->statu  != 7)
                    <div class="btn-group btn-group-sm">
                        <a href="#" wire:click="down({{ $DRG->id }})" class="btn btn-primary"><i class="fa fa-minus"></i></a>
                    </div>
                    @endif
                    {{ $DRG->sheet_qty_done }}
                    @if($DRG->statu  != 7)
                    <div class="btn-group btn-group-sm">
                        <a href="#" wire:click="up({{ $DRG->id }})" class="btn btn-secondary"><i class="fa fa-plus"></i></a>
                    </div>
                    @endif
                </td>
                <td>{{ $DRG->unit_time }} h</td>
                <td>{{ $DRG->TotalTime() }} h</td>
                <td>{{ $DRG->RemaningTotalTime() }} h</td>

                @php
                    $totalTime += $DRG->TotalTime();
                    $totalRemaningTime += $DRG->RemaningTotalTime();
                @endphp

                <td class="project-actions">
                    
                    @if($DRG->statu  != 7)
                    <a class="btn btn-success btn-sm" href="#"  wire:click="cut({{$DRG->id}})"><i class="fas fa-folder"></i>All Cut</a>
                    <a class="btn btn-warning btn-sm" href="#" wire:click="stop({{$DRG->id}})"><i class="fas fa-trash"></i>Stop</a>
                    @else
                    <a class="btn btn-success btn-sm" href="#" wire:click="run({{$DRG->id}})"><i class="fas fa-trash"></i>Relancer</a>
                    @endif
                    <a class="btn btn-info btn-sm" href="#"><i class="fas fa-pencil-alt"></i>Edit</a>
                    <a class="btn btn-danger btn-sm" href="#" wire:click="delete({{$DRG->id}})"><i class="fas fa-trash"></i>Delete</a>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="13">Aucun programme plannfiée<td>
                <tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Programme Progress</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Nombre de tôle</th>
                <th>Nombre de tôle coupée</th>
                <th></th>
                <th>Temps Total</th>
                <th>Temps Restant</th>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <td class="project_progress">
                    @if ($totalTime > 0) 
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ 100-($totalRemaningTime/$totalTime*100) }}" 
                                                                                    aria-valuemin="0" 
                                                                                    aria-valuemax="100" 
                                                                                    style="width: {{ 100-($totalRemaningTime/$totalTime*100) }}%">
                            </div>
                        </div>
                        <small>
                                {{ round(100-($totalRemaningTime/$totalTime*100),2) }}% Complete
                        </small>
                    @endif
                </td>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>{{ $totalTime }} h</th>
                <th>{{ $totalRemaningTime }} h</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
