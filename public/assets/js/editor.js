$(document).ready(function () {
    // Summernote basic
    var _basic = '.summernote-basic';
    if ($(_basic).length) {
        $(_basic).each(function () {
            $(this).summernote({
                placeholder: 'متن نامه',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'strikethrough', 'clear']],
                    ['font', ['superscript', 'subscript']],
                    ['color', ['color']],
                    ['fontsize', ['fontsize', 'height']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link',]],
                    ['view', ['fullscreen',]],
                ],
                callbacks: {
                    onChange: function(contents, $editable) {
                        $('.letter-body').html(contents);
                    }
                },
                defaultFontName: 'dana'
            });
        });
    }

});
