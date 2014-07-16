define(['domReady', 'modules/widget/TaskStatus'], function (domReady, TaskStatus) {
    domReady(function () {
        $('#task_filter select').selectpicker();
        $('#task_filter .input-daterange').datepicker({
            todayBtn: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
        
        var taskStatus = new TaskStatus();
        taskStatus.init();
    });
});