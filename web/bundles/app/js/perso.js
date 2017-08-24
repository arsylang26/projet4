/**
 * Created by jafa on 14/07/2017.
 */
$.fn.datepicker.dates['fr'] = {
    days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    daysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
    daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
    months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
    monthsShort: ["Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec"],
    today: "Aujourd'hui",
    clear: "effacer",
    format: "dd-mm-yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};
$('.js-datepicker').datepicker(
    {
        format: 'dd-mm-yyyy',
        language: 'fr',
        todayBtn: 'linked',
        startDate: 'd',
        datesDisabled: ['1-05-2018', '1-11-2017', '25-12-2017'],
        daysOfWeekDisabled: '0,2'
    }
);
$('.js-datepicker-birthdate').datepicker(
    {
        format: 'dd-mm-yyyy',
        language: 'fr',
        startView: 'decade',
        startDate: '-100y',
        endDate: '-1m'
    }
);
//mise en forme des flash messages
$('[class^="flash"]').delay(3000).fadeOut(1000, 'swing').css({"border":"1px solid blue","background-color":"lightblue","text-align":"center"});

//personalisation du bouton pay with card de stripe
$(".stripe-button-el span").remove();
$("button.stripe-button-el").removeAttr('style').css({
    "background-image": "linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4)",
    "padding": "6px 12px",
    "color": "white",
    "font-size": "1em"
}).html("Procéder au paiement");

//personalisation de l'adresse courriel du client dans le modal stripe
$("span.Header-loggedInEmail").removeAttr('style').css({
    "color": "red",
    "font-weight": "bold"
});

// levée d'un message au click de la checkbox "tarif réduit"
$('input[type=checkbox]').click(function () {
    if (this.checked) {

      $(this).parent().append("<p id='info'><strong>Le tarif réduit ne peut s\'appliquer qu\'aux étudiants, militaires, personnels du musée."+
          "Un justificatif sera demandé lors de votre visite</strong></p>");
    }else{
        $('#info').remove();
    }

});





// function displayDiscountPopup() {
//
// var $message='Le tarif réduit ne peut s\'appliquer qu\'aux étudiants, militaires, personnels du musée.<br> Un justificatif sera demandé lors de votre visite';
//     $('body').append('<div id="discountWarning" title="Avertissement"></div>');
//     $("#discountWarning").html($message);
//
//
//
//     var popup = $("#discountWarning").dialog({
//         autoOpen: true,
//         width: 400,
//         dialogClass: 'dialogstyle',
//         buttons: [
//             {
//                 text: "OK",
//                 "class": 'ui-state-warning',
//                 click: function () {
//                     $(this).dialog("close");
//                     $('#discountWarning').remove();
//                 }
//             }
//         ]
//     });
//     $("#discountWarning").prev().addClass('ui-state-warning');
//     return popup;
//
// }
//
// $('input[type=checkbox]').click(function () {
//     if (this.checked) {
//
//         $(displayDiscountPopup());
//
// });
