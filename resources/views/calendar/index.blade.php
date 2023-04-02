<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.6/css/bootstrap-colorpicker.css"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.6/js/bootstrap-colorpicker.js"></script>




    <title>Document</title>
</head>

<body>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="sssssssssssss">
        Launch demo modal
    </button>
    <!-- Modal -->
    <div class="modal fade" id="bookingModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Booking title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="title" id="title" class="form-control"
                        placeholder="Add Title......">
                    <small class="text-danger title_error" id="title_error"></small>
                    <br>
                    <div id="cp2" class="input-group colorpicker-component">
                        <input type="text" value="#00AABB" class="form-control" id="color_val" name="color"
                            hidden />
                        <span class="input-group-addon"><i></i></span>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="save_btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mt-5">calendar</h3>
                <div class="col-md-11 offset-1 mt-5 mb-5">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            var booking = @json($events);
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',
                },
                events: booking,
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    $('#bookingModel').modal("toggle");
                    $('#save_btn').click(function() {
                        var title = $('#title').val();
                        var color_val = $('#color_val').val();
                        var start_date = moment(start).format('YYYY-MM-DD');
                        var end_date = moment(end).format('YYYY-MM-DD');
                        $.ajax({
                            url: "{{ route('calendar.store') }}",
                            data: {
                                title,
                                start_date,
                                end_date,
                                color_val
                            },
                            method: 'POST',
                            dataType: 'json',
                            beforeSend: function() {},
                            success: function(response) {
                                toastr.success('تم الحفظ');
                                console.log(response);
                                $('#calendar').fullCalendar('renderEvent', {
                                    'id': response.id,
                                    'title': response.title,
                                    'start': response.start_date,
                                    'end': response.end_date,
                                    'color': response.color,
                                });
                                $('#bookingModel').modal("hide");

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $.each(jqXHR.responseJSON.errors, function(key,
                                    val) {
                                    $("#" + key + "_error").text(val[0]);
                                    $('input[name=' + key + ']').addClass(
                                        'is-invalid');
                                });
                            },
                        });
                    });

                },
                editable: true,
                // timeFormat: moment().format('LT'),
                displayEventTime: false,
                eventDrop: function(event) {
                    console.log(event);
                    var id = event.id;
                    var start_date = moment(event.start).format("Y-MM-DD HH:mm:ss");
                    var end_date = moment(event.end).format("Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: "{{ route('calendar.update', '') }}" + '/' + id,
                        data: {
                            start_date,
                            end_date
                        },
                        method: 'PATCH',
                        dataType: 'json',
                        beforeSend: function() {},
                        success: function(response) {
                            console.log(response);
                            // toastr.success(response.message);
                            swal(response.message, {
                                icon: "success",
                                timer: 500
                            });


                        },
                        error: function(error) {
                            console.log(error);
                        },
                    });
                },
                eventClick: function(event) {
                    var id = event.id;
                    swal({
                            title: "Are you sure Delete?",
                            // text: "Once deleted, you will not be able to recover this imaginary file!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url: "{{ route('calendar.destroy', '') }}" + '/' + id,
                                    method: 'DELETE',
                                    dataType: 'json',
                                    beforeSend: function() {},
                                    success: function(response) {
                                        console.log(response.data);
                                        $('#calendar').fullCalendar('removeEvents',
                                            response.data)
                                        swal(response.message, {
                                            icon: "success",
                                            timer: 500
                                        });
                                    },
                                    error: function(error) {
                                        console.log(error);
                                    },
                                });
                            }
                        });

                },

                eventMouseover: function(event) {
                    $('.fc-event-inner', this).append('<div id=\"' + event.id +
                        '\" class=\"hover-end\">' + $.fullCalendar.formatDate(event.end, 'h:mmt') +
                        '</div>');
                    console.log('Mouse over.');
                    // alert(event);
                    console.log(event);
                }



                // selectAllow: function(event) {
                //     return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1,
                //         'second').utcOffset(false), 'day');
                //     //ايقاف الوقت الطويل
                // },

            });
            $('#bookingModel').on('hidden.bs.modal', function(e) {
                $('#save_btn').unbind();
                $('#title').val('');
            });
            $('.fc-event').css('font-size', '16px');
            // $('.fc-event').css('width', '100px');
            $('#cp2').colorpicker();

            $("#sssssssssssss").click(function() {
                $('#calendar').fullCalendar().rerender();
            });
            // $('.fc-event').css('border-radius' , '50%');
        });
    </script>
</body>

</html>
