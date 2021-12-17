@extends('layouts.main')
@section('title')
    Jadwal Booking Tidak Tersedia
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Jadwal Booking Tidak Tersedia</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Jadwal Booking Tidak Tersedia</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Table Jadwal Booking Tidak Tersedia</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-5">
                                    <form action="{{ route('schedule_unavailable_booking.store') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">Mulai Dari</label>
                                            <input type="datetime-local" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}" name="start_at" class="form-control" id="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Sampai Dengan</label>
                                            <input type="datetime-local" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}" name="end_at" class="form-control" id="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Catatan</label>
                                            <textarea name="note" id="" class="form-control" cols="10" rows="3"></textarea>
                                        </div>
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-success w-100">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Catatan</th>
                                                <th>Mulai</th>
                                                <th>Sampai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($schedule_unavailable_bookings as $i)
                                                <tr>
                                                    <td>
                                                        <p data-toggle="tooltip" title="{{ $i->note }}">
                                                            {{ strlen($i->note) > 21 ? substr($i->note, 0, 25) . '...' : $i->note }}
                                                        </p>
                                                    </td>
                                                    <td>{{ $i->start_at }}</td>
                                                    <td>{{ $i->end_at }}</td>
                                                    <td>
                                                        <form
                                                            action="{{ route('schedule_unavailable_booking.destroy', $i->id) }}"
                                                            class="form-delete" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="badge badge-danger border-0">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-7">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('plugins/fullcalendar/main.js') }}"></script>
    <script>
        const newSchedule = {
            start_at: null,
            end_at: null
        }
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            themeSystem: 'bootstrap',
            events: [
                @foreach ($schedule_unavailable_bookings as $s)
                    {
                    title: '{{ $s->note }}',
                    start: '{{ $s->start_at }}',
                    end: '{{ $s->end_at }}'
                    },
                @endforeach
                newSchedule
            ],

        });
        calendar.render();

        document.querySelectorAll('.form-delete').forEach(function(elem) {
            elem.addEventListener('submit', function() {
                let isConfirm = confirm('Apakah anda yakin untuk menghapus jadwal ini?')

                if (isConfirm) {
                    return true
                }
                return false
            })
        })

        document.querySelector('[name=start_at]').addEventListener('input', function(elem) {
            newSchedule.start_at = elem.target.value
            calendar.refetchEvents()
            calendar.render()
            console.log(calendar.getEventSources())
        })

        document.querySelector('[name=end_at]').addEventListener('input', function(elem) {
            newSchedule.end_at = elem.target.value
            calendar.refetchEvents()
            calendar.render()
            console.log(calendar.getEventSources())
        })
    </script>
    <script>
        $(function() {
            $('.select2').select2()
        })
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    </script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "searching": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
