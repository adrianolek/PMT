define(['domReady', 'modules/widget/TaskStatus', 'modules/helper/Url'], function (domReady, TaskStatus, urlHelper) {
    domReady(function () {
        $('#task_filter select').selectpicker();
        $('#task_filter .input-daterange').datepicker({
            todayBtn: true,
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        if($('#tasks_list').data('order') == 'priority')
        {
            $('#tasks_list tbody').addClass('sortable').sortable({
                handle: '.priority',
                axis: 'y',
                helper: function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                },
                start: function(e, ui)
                {
                    ui.placeholder.height(ui.item.height());
                },
                stop: function(e, ui){
                    $.ajax({
                        type: "POST",
                        url: urlHelper.urlFor('tasks/order'),
                        data: { 'item': ui.item.data('id'),
                            'prev': ui.item.prev('tr').data('id'),
                            'next': ui.item.next('tr').data('id')
                        },
                        dataType: 'json',
                        success: function(data){
                            $('#task_'+data.id+' .priority').animate({'backgroundColor': data.color}, 500);
                        }
                    });
                },
                placeholder: 'sortable-placeholder'
            });
        }
        
        var taskStatus = new TaskStatus();
        taskStatus.init();
    });
});