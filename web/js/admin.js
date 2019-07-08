$(function(){

    $(document).on('change', '.form-toggle-location input[type="checkbox"]', function(){

        let form = $(this).closest('form');
        let locationId = $(this).val();

        $.post(form.attr('action'), {id: locationId}, (data) => {});
    });
});