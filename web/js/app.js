var app = {
    search: {
      side: function(path) {
        $(function(){
          $('#search_term').select2({
            placeholder: 'Search...',
            minimumInputLength: 3,
            dropdownCssClass: 'bigdrop',
            ajax: {
              url: path,
              dataType: 'json',
              quietMillis: 100,
              data: function (term, page) {
                return {
                  term: term,
                  page: page
                };
              },
              results: function (data, page) {
                return data;
              }
            }
          }).on('change', function(e){
              if(e.added) {
                window.location = e.added.url;
              }
          });
        });
      }  
    },
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