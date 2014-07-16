define(['domReady', 'modules/widget/TaskStatus'], function (domReady, TaskStatus) {
    domReady(function () {        
        var taskStatus = new TaskStatus();
        taskStatus.init();
    });
});