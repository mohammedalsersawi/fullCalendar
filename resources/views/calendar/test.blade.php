// خصائص التقويم

{{-- اخفاء الحدث عنةالنقر عليه للمرة الاولى ومن عرضه مرة اخري عند النقر عليه مرة اخرى --}}
{{-- eventMouseover: function(event, jsEvent, view) {
    var myTarget = $(jsEvent.target);

    if (!myTarget.hasClass('fc-event')) {
        myTarget = myTarget.closest('.fc-event');
    }
    myTarget.css("display","inline-table");
},
eventMouseout: function(event, jsEvent, view) {
    var myTarget = $(jsEvent.target);

    if (!myTarget.hasClass('fc-event')) {
        myTarget = myTarget.closest('.fc-event');
    }
    myTarget.css("display","initial");
} --}}


{{-- تعطيل خاصية اضافة الوقت الطويل --}}
{{-- selectAllow: function(event) {
    return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1,
        'second').utcOffset(false), 'day');
    //ايقاف الوقت الطويل
}, --}}
