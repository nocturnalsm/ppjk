@extends('layouts.body')
@section('content')
<div class="container-fluid">
    <div class="modal fade" id="modal" style="z-index:1200" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="d-none btn btn-ok btn-secondary btn-sm waves-effect waves-light"></button>
                <button type="button" class="btn btn-close btn-secondary btn-sm waves-effect waves-light" data-dismiss="modal">Tutup</button>
            </div>
        </div>
        </div>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach($breads as $brd)
            <li class="breadcrumb-item{{ isset($brd['active']) ? 'active' : '' }}">
                @if (isset($brd['link']))
                    <a href="{{ $brd['link'] }}">
                @endif
                {{ $brd['text'] }}
                @if(isset($brd['link']))
                    </a>
                @endif
            </li>
            @endforeach
        </ol>
    </nav>
    @yield('main')
</div>
@endsection
@push('stylesheets_end')
    <link href="{{ asset('datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables/css/jquery.dataTables_themeroller.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables/Buttons-1.5.4/css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables/Select-1.2.6/css/select.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('datatables/Responsive-2.2.2/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
    <script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatables/Buttons-1.5.4/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatables/Select-1.2.6/js/dataTables.select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatables/Responsive-2.2.2/js/dataTables.responsive.min.js') }}"></script>
@endpush
