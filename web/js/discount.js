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

    $(document).on('change', '.category-radio', function(){

        let form = $(this).closest('form');

        $.post(form.attr('action'), form.serialize(), (data) => {

            if (data.status !== 'success') {
                console.log('Не удалось прикрепить скидку к категории');
            }

        }, 'json');

        return false;
    });


});