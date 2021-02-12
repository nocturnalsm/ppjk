@extends('layouts.base') 
@section('main')
    <div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="modalform" aria-hidden="true">
        <div class="modal-dialog {{ $modalsize ?? 'modal-md' }}" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">                    
                    @stack('formbody')                
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button id="saveform" class="btn btn-primary">Simpan</button>
                    <button class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    <table width="100%" id="grid" class="table display" width="100%">
    <thead>
        <tr> 
        @foreach($columns as $col)
            <th>{{ $col }}</th>
        @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
@endsection