define(['modules/helper/Url'], function (urlHelper) {
    return function () {
        var TaskStatus = {
            init: function () {
                $(document).on('click', '.status-change a', function (e) {
                    e.preventDefault();
                    var dropdown = $(this).parents('.status-change');

                    $.ajax({
                        type: "POST",
                        url: urlHelper.urlFor('task/' + $(this).data('task') + '/status'),
                        data: {
                            'status': $(this).data('status')
                        },
                        dataType: 'html',
                        success: function (data) {
                            dropdown.replaceWith(data);
                        }
                    });
                });
            }
        };
        return TaskStatus;
    };
});