$(function(){

    let modalAttachToCategory = $('#modalAttachDiscountToCategory');

    $(document).on('click', '.attach-discount-to-category', function(e){
        e.preventDefault();

        $.get($(this).attr('href'), function(data){

            if ($.trim(data)) {

                modalAttachToCategory.find('.modal-body').html(data);

                modalAttachToCategory.modal('show');
            }
        });

        return false;
    });


    $(document).on('submit', '.form-attach-to-category', function(){

        let form = $(this);

        $.post(form.attr('action'), form.serialize(), function(data){

            if (data) {

                modalAttachToCategory.modal('hide');

                $.pjax.reload('#discounts');
            }
        });

        return false;
    });
});