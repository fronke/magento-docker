define([
    'jquery'
], function ($) {
    'use strict';

    function processQuestions(url) {
        $.ajax({
            url: url,
            cache: true,
            dataType: 'html'
        }).done(function (data) {
            $('#product-question-container .ajax-remove').remove();

            $('#product-question-container').append(data);

            $('#product-question-container .pages a').each(function (index, element) {
                $(element).unbind('click');
                $(element).click(function (event) {
                    $(element).addClass('disabled');
                    processQuestions($(element).attr('href'));
                    event.preventDefault();
                });
            });

            $("#product-question-container .block-top").each(function (index, element) {
                $(element).unbind('click');
                initQuestionCollapse(element);
            });
        });
    }

    function initQuestionCollapse(element) {
        $(element).click(function() {
            $(this).closest(".block-content").toggleClass( "collapsed" );
        });
    }

    return function (config, element) {
        processQuestions(config.productQuestionUrl);
    };
});
