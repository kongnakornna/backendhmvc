$(function() {
    var flag = 'primal';
    $('.primal-class').click(function() {
        $('.middle').hide();
        $('.primal').show();
        $('.class').removeClass('active');
        $('.middle').removeClass('active');
        $(this).addClass('active');
        flag = 'primal';
    });
    $('.middle-class').click(function() {
        $('.primal').hide();
        $('.middle').show();
        $('.class').removeClass('active');
        $('.primal').removeClass('active');
        $(this).addClass('active');
        flag = 'middle';
    });
    $('.exa-subject').click(function() {
        $('.exa-subject').removeClass('active');
        $(this).addClass('active');
    });
    
});