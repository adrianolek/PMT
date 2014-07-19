define(['domReady'], function (domReady) {
    domReady(function () {
        $('#track_filter .input-daterange').datepicker({
            todayBtn: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
    });
});