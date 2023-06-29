{{ content() }}
{{ assets.outputCss('fullCalendar') }}
{{ assets.outputJs('fullCalendar') }}
<script>
    $(document).ready(function(){
        $('#calendar').fullCalendar({
            header:{
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            defaultDate: '2023-06-20',
            editable: true,
            eventLimit: true,
            events: '{{  url('appointments/json') }}'
        });
    });
</script>
<div id="calendar"></div>