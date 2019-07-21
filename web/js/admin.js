$(function(){

    $(document).on('change', '.form-toggle-location input[type="checkbox"]', function(){

        let form = $(this).closest('form');
        let locationId = $(this).val();

        $.post(form.attr('action'), {id: locationId}, (data) => {});
    });

    $(document).on('click', '.view-set-capital-form', function(e){
        e.preventDefault();

        $.get($(this).attr('href'), (data) => {

            let modal = $('#modalManageRegion');
            modal.find('.modal-title').text('Установить столицу региона');
            modal.find('.modal-body').html(data);
            modal.modal('show');
        });

        return false;
    });
});