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