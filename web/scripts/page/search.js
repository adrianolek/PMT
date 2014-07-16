define(['domReady', 'modules/helper/Url'], function(domReady, urlHelper){
    return function (path) {
        domReady(function(){
            $('#search_term').select2({
                placeholder: 'Search...',
                minimumInputLength: 3,
                dropdownCssClass: 'bigdrop',
                ajax: {
                    url: urlHelper.urlFor('search'),
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
    };
});