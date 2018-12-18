$(function(){

    let modalManageWordKey = $('#modalManageWordKey');

    $(document).on('click', '.load-add-key-form', function(e){
        e.preventDefault();

        $.get($(this).attr('href'), function(data){

            if ($.trim(data)) {

                modalManageWordKey.find('.modal-title').text('Добавить ключ');

                modalManageWordKey.find('.modal-body').html(data);

                modalManageWordKey.modal('show');
            }
        });

        return false;
    });

    $(document).on('submit', '.form-add-key', function(){

        let form = $(this);

        $.post(form.attr('action'), form.serialize(), function(data){

            if (data.status === 'success') {

                modalManageWordKey.modal('hide');

                $.pjax.reload('#categories');
            }

        }, 'json');

        return false;
    });

    $(document).on('submit', '.form-add-key', function(){

        let form = $(this);

        $.post(form.attr('action'), form.serialize(), function(data){

            if (data.status === 'success') {

                modalManageWordKey.modal('hide');

                $.pjax.reload('#categories');
            }
        }, 'json');

        return false;
    });

    $(document).on('click', '.delete-key', function(e){
        e.preventDefault();

        $.post($(this).attr('href'), function(){

            $.pjax.reload('#categories');
        });

        return false;
    });
});