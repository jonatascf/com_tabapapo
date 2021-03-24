jQuery(function() {
    document.formvalidator.setHandler('title',
        function (value) {
            regex=/^[^0-9]+$/;
            return regex.test(value);
        });
});