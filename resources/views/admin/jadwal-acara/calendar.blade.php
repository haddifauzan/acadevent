@extends('admin.layout.master')

@section('title', 'Kalender Acara')

@section('content')
<div class="container">
    <h1 class="text-center mb-3">Kalender Acara</h1>

    <div id="calendar"></div>
    <!-- Modal untuk detail acara -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="eventDetailLabel">Detail Acara</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Nama Acara:</strong> <span id="eventName"></span></p>
            <p><strong>Deskripsi:</strong> <span id="eventDescription"></span></p>
            <p><strong>Jenis Acara:</strong> <span id="eventType"></span></p>
            <p><strong>Tingkat:</strong> <span id="eventLevel"></span></p>
            <p><strong>Waktu Mulai:</strong> <span id="eventStartTime"></span></p>
            <p><strong>Waktu Selesai:</strong> <span id="eventEndTime"></span></p>
            <p><strong>Status Acara:</strong> <span id="eventStatus" class="text-capitalize"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
<!-- FullCalendar CSS and JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        locale: 'id',  // Locale Indonesia
        events: @json($events),  // Ambil data acara dari controller
        eventColor: '#032B44',  // Warna background acara diubah menjadi biru
        eventTextColor: '#000',  // Warna teks acara diubah menjadi hitam
        eventClick: function(info) {
            // Tampilkan modal dengan detail acara
            $('#eventDetailModal').find('#eventName').text(info.event.title);
            $('#eventDetailModal').find('#eventDescription').text(info.event.extendedProps.description);
            $('#eventDetailModal').find('#eventType').text(info.event.extendedProps.jenis_acara);
            $('#eventDetailModal').find('#eventLevel').text(info.event.extendedProps.tingkat);
            $('#eventDetailModal').find('#eventStartTime').text(info.event.start.toLocaleString());
            $('#eventDetailModal').find('#eventEndTime').text(info.event.end.toLocaleString());
            $('#eventDetailModal').find('#eventStatus').text(info.event.extendedProps.status_acara);
            $('#eventDetailModal').modal('show');
        },
        eventRender: function(info) {
            $(info.el).addClass('rounded');  // Tambahkan rounded corner pada acara
        }
    });

    calendar.render();
});

</script>
