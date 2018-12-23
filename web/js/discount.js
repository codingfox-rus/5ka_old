$(function(){

    let modal = $('#modalDiscountCategory');

    $(document).on('click', '.load-create-category-form', function(e){
        e.preventDefault();

        $.get($(this).attr('href'), function(data){

            if ($.trim(data)) {

                modal.find('.modal-title').text('Добавить категорию');

                modal.find('.modal-body').html(data);

                modal.modal('show');
            }
        });

        return false;
    });

    $(document).on('submit', '.form-create-category-for-discount', function(){

        let form = $(this);

        $.post(form.attr('action'), form.serialize(), function(data){

            if (data.status === 'success') {

                $.pjax.reload('#discountCategory');

                modal.modal('hide');

            } else if (data.status === 'error') {

                console.log(data.errors);
            }

        }, 'json');

        return false;
    });

    $(document).on('submit', '.form-add-key-for-discount', function(){

        let form = $(this);

        $.post(form.attr('action'), form.serialize(), function(data){

            if (data.status === 'success') {

                $.pjax.reload('#discountCategory');

            } else if (data.status === 'error') {

                console.log(data.errors);
            }

        }, 'json');

        return false;
    });

    // todo: проверить возможность использования, если что удалить

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