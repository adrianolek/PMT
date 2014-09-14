define(['domReady', 'modules/widget/TaskStatus'], function (domReady, TaskStatus) {
    domReady(function () {
        var taskStatus = new TaskStatus();
        taskStatus.init();

        $('#comment-form').submit(function (e) {
            var form = $(this);
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function (data) {
                    $('#comments-list').append(data);
                },
                error: function(xhr) {
                    form.replaceWith(xhr.responseText);
                }
            });
        });
    });
});