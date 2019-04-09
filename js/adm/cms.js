var cms = {};
cms.switchItemForm = function (name) {
    console.log('cms.switchItemForm(' + name + ')');
    $('.menuItemForm').each(function () {
        if ($(this).css('display') == 'block') {
            $(this).fadeOut(150, function () {
                $('#' + name + 'Form').fadeIn(150);
            });
        }
    })
};
