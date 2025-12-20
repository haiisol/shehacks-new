(function ($) {
    "use strict";
    
    $(document).ready(function () {
        
        // datepicker
        if($('.datepicker').length) {
            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                endDate: 'today'
            });
        }

        // select
        $('.select-custome').select2({
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            minimumResultsForSearch: -1
        });

        // select search
        $('.select-custome-search').select2({
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });

    });
})(jQuery);