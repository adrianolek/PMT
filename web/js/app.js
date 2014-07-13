var app = {
    tasks: {
      status: function(path) {
        $(function(){
          $(document).on('click', '.status-change a', function(e){
            e.preventDefault();
            var dropdown = $(this).parents('.status-change');

            $.ajax({
              type: "POST",
              url: path.replace('id', $(this).data('task')),
              data: {
                'status': $(this).data('status')
              },
              dataType: 'html',
              success: function(data){
                dropdown.replaceWith(data);
              }
            });
          });
        });
      }
    }
}