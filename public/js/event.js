var _token = $("meta[name=_token]").attr('content')
var today = new Date();

$(function() {
  $(".datepick2--event").datetimepicker({
    format: "mm/dd/yyyy H P",
    autoclose: true,
    minView: 1,
    startView: "month",
    startDate: today,
    endDate: new Date(today.getFullYear(), today.getMonth()+3, today.getDate()),
    showMeridian: true
  });
  $('#duration-event').durationPicker({
    lang: 'en',
    formatter: function (s) {
    return s;
    },
    showSeconds: false
  });
  $(document).ready(function(){
    $('[data-toggle="popover"]').popover();
  });
})

function fetchEvent() {
  axios({
    method: 'post',
    responseType: 'json',
    url: base_url + 'ajax/get-event-by-date',

    data: {
      _token: _token,
      start_date: start_date()
    }
  }).then( function (response) {
    if (response.status ==  200) {
      var events = [];
      var allEvents = response.data[0].events
      for (i in allEvents) {
        var y= allEvents[i].start_date.substr(0,4);
        var m = allEvents[i].start_date.substr(5,2);
        m = parseInt(m)
        var d = allEvents[i].start_date.substr(8,2);
        events.push({'Date': new Date(y, m-1, d), 'Title': allEvents[i].timeline.name})
      }
      var element = document.getElementById('caleandar');
      caleandar(element, events, {});
    }
  }).catch(function(error) {
    console.log(error)
  })
}

var start_obj = new Date(today.getFullYear(), today.getMonth()-2, today.getDate());
var start_date = function () {
  if(start_obj.getMonth() < 10){
    return start_obj.getFullYear() + '-0' + (start_obj.getMonth()+1) + '-' + (start_obj.getDate()<10?'0'+start_obj.getDate():start_obj.getDate())
  }
  return start_obj.getFullYear() + '-' + (start_obj.getMonth()+1)+ '-' + (start_obj.getDate()<10?'0'+start_obj.getDate():start_obj.getDate())
}
var element = document.getElementById('caleandar')
if(element!== null) {
  fetchEvent()
}
