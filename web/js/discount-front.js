$(function(){

    let stripeWidth = $('.regular-price-stripe').width();

    $('.discount-prices').each(function(){

        let regularPrice = $(this).data('rprice');
        let specialPrice = $(this).data('sprice');

        let discountStripeWidth = Math.round(specialPrice * stripeWidth / regularPrice);

        $(this).find('.discount-price-stripe').css('width', discountStripeWidth);
    });

});