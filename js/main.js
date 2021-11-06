$(document).ready(function(){
   var table1 = document.getElementById('summary01');
   var table2 = document.getElementById('summary02');

   var total_users_p   = $("#total_users");
   var total_pages_p   = $("#total_pages");
   var page_size_p     = $("#page_size");
   var current_page_p  = $("#current_page");
   var time_left_p     = $("#time_left");
   var total_time_p    = $("#total_time");
   var time_percent_p  = $("#time_percent")
   var requests_done_p = $("#requests_done");
   var req_percent_p   = $("#request_percent");
   var progress_c2     = $("#progress_c2");
   var progress_c3     = $("#progress_c3");
   var progress_c4     = $("#progress_c4");

   var percent       = 0;
   var total_users   = 0;
   var total_pages   = 0;
   var page_size     = 30;
   var current_page  = 0;
   var requests_done = 0;
   var start_date    = getMoment();

   var message  = $('#id_message').html();
   var keyboard = $('#id_keyboard').text();
   var method   = $('#id_method').text();
   var url      = $('#id_url').text();

   var host_url = 'http://bulk.loc'
      $.ajax({
                url:  host_url + '/php/post.php',
                type: 'POST',
                async: false,
                cache: false,
                data: {
                    getCount: 1
                },
                success: function (res) {

                    var obj     = JSON.parse(res);

                    total_users = obj[0]['total_users'];
                    total_pages = Math.ceil(total_users / 30);

                    total_users_p.text(total_users);
                    total_pages_p.text(total_pages);
                    
                    current_page_p.text(current_page+1);
                    total_time_p.text(secondsTimeSpanToHMS(total_pages*3));

                    var limitInterval = window.setInterval(function(){
                        $.ajax({ 
                            url:  host_url + '/php/post.php?offset=' + (current_page * 30),
                            type: 'POST',
                            async: false,
                            cache: false,
                            data: {
                                message:  message,
                                keyboard: keyboard,
                                method:   method,
                                url:      url 
                            },
                               success: function (data, textStatus, xhr) {
                                    
                                    nextPage();

                                    percent = Math.round((current_page / total_pages) * 10000)/100 + "%";
                                    time_left_p.text(secondsTimeSpanToHMS((total_pages-current_page)*3));
                                    time_percent_p.text(percent);

                                    total_pages_p.text(total_pages);
                                    current_page_p.text(current_page);
                                    page_size_p.text(percent);
                                    progress_c2.css("width",percent);
                                    progress_c3.css("width",percent);
                                    progress_c4.css("width",percent);

                                    requests_done_p.text(current_page * page_size);
                                    req_percent_p.text(percent);

                                    if(current_page >= total_pages){
                                        $("#status_badge").removeClass("badge-warning");
                                        $("#status_badge").addClass("badge-success");
                                        $("#status_badge").text("Ready");
                                        requests_done_p.text(total_users);
                                        table1.rows[1].cells[0].innerText = total_users;

                                        table2.rows[1].cells[0].innerText = total_pages;
                                        table2.rows[1].cells[1].innerText = current_page;

                                        table2.rows[1].cells[4].innerText = start_date;
                                        table2.rows[1].cells[5].innerText = getMoment();
                                        table2.rows[1].cells[6].innerText = timeDiff(start_date, getMoment());
                                        readSummary();
                                        clearInterval(limitInterval);
                                        return false;
                                    }
                               },
                               error: function (jqXHR, textStatus, errorThrown) {
                                    clearInterval(limitInterval);
                                    alert('ERROR');
                                    // if (jqXHR.status == 500) {
                                    //     alert('Internal error: ' + jqXHR.responseText);
                                    // } else {
                                    //     alert('Unexpected error.');
                                    // }
                                }
                            }); // inner ajax body
                    }, 1000); // interval body
                } //first ajax success
            }); //first ajax body

function nextPage(){
    current_page += 1;
}

function readSummary(total_users){
      $.ajax({ 
        url:   host_url + '/php/summary.php',
        async: false,
        cache: false,
        success: function (data) {
            var res = data.split(",");
            table1.rows[1].cells[1].innerText = res[0];
            table1.rows[1].cells[2].innerText = (parseInt(res[3]) - parseInt(res[0]));
            table1.rows[1].cells[3].innerText = Math.round((res[0] / res[3]) * 10000)/100 + ' %';
            table1.rows[1].cells[4].innerText = res[1];
            table1.rows[1].cells[5].innerText = res[2];
            
            table1.rows[1].cells[6].innerText = 0;
            table2.rows[1].cells[2].innerText = (parseInt(res[3]));
            table2.rows[1].cells[3].innerText = (parseInt(res[3]));

        },                      
     }); 
}
  
function secondsTimeSpanToHMS(s) { 
  var h = Math.floor(s / 3600); //Get whole hours
  s -= h * 3600;
  var m = Math.floor(s / 60); //Get remaining minutes
  s -= m * 60;
  return h + ":" + (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s); //zero padding on minutes and seconds
}

function getMoment(){
   var time = new Date();
var timer = ("0" + time.getHours()).slice(-2)   + ":" + 
    ("0" + time.getMinutes()).slice(-2) + ":" + 
    ("0" + time.getSeconds()).slice(-2);
    return timer;
}

function timeDiff(startTime, endTime){

    var todayDate = moment(new Date()).format("MM-DD-YYYY");     
    var startDate = new Date(`${todayDate} ${startTime}`);
    var endDate   = new Date(`${todayDate } ${endTime}`);
    var timeDiff  = Math.abs(startDate.getTime() - endDate.getTime());

    var hh = Math.floor(timeDiff / 1000 / 60 / 60);   
    hh = ('0' + hh).slice(-2)
   
    timeDiff -= hh * 1000 * 60 * 60;
    var mm = Math.floor(timeDiff / 1000 / 60);
    mm = ('0' + mm).slice(-2)

    timeDiff -= mm * 1000 * 60;
    var ss = Math.floor(timeDiff / 1000);
    ss = ('0' + ss).slice(-2)
    return hh + ":" + mm + ":" + ss;
   }
});
