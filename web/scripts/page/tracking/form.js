define(['domReady'], function (domReady) {
    domReady(function () {
        $('#track_date').datepicker({
            todayBtn: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });
});