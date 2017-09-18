//mise en forme des calendriers de saisie
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

function Holidays(year) {
    var NewYearDay = new Date(year, "00", "01");
    var LabourDay = new Date(year, "04", "01");
    var Victoire1945 = new Date(year, "04", "08");
    var FeteNationale = new Date(year, "06", "14");
    var Assomption = new Date(year, "07", "15");
    var Toussaint = new Date(year, "10", "01");
    var Armistice = new Date(year, "10", "11");
    var Noel = new Date(year, "11", "25");
    var G = year % 19;
    var C = Math.floor(year / 100);
    var H = (C - Math.floor(C / 4) - Math.floor((8 * C + 13) / 25) + 19 * G + 15) % 30;
    var I = H - Math.floor(H / 28) * (1 - Math.floor(H / 28) * Math.floor(29 / (H + 1)) * Math.floor((21 - G) / 11));
    var J = (year * 1 + Math.floor(year / 4) + I + 2 - C + Math.floor(C / 4)) % 7;
    var L = I - J;
    var MoisPaques = 3 + Math.floor((L + 40) / 44);
    var JourPaques = L + 28 - 31 * Math.floor(MoisPaques / 4);
    var LundiPaques = new Date(year, MoisPaques - 1, JourPaques + 1);
    var Ascension = new Date(year, MoisPaques - 1, JourPaques + 39);
    var LundiPentecote = new Date(year, MoisPaques - 1, JourPaques + 50);

    return [NewYearDay, LabourDay, LundiPaques, Victoire1945, Ascension, LundiPentecote, FeteNationale, Assomption, Toussaint, Armistice, Noel];
}

var currentYear = (new Date).getFullYear();
var holidays = Holidays(currentYear).concat(Holidays(currentYear+1));

$('.js-datepicker').datepicker(
    {
        format: 'dd-mm-yyyy',
        language: 'fr',
        todayBtn: 'linked',
        startDate: 'd',
        // datesDisabled: ['1-05-2018', '1-11-2017', '25-12-2017', '1-01-2018', '8-05-2018', '14-07-2018', '15-08-2018'],
        daysOfWeekDisabled: '0,2',
        beforeShowDay: function (date) {
            for (i = 0; i < holidays.length; i++) {
                if (date.getTime() == holidays[i].getTime()) {
                    return false;
                }
            }
            return true;
        }


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
$('[class^="flash"]').delay(3000).fadeOut(1000, 'swing').css({
    "border": "1px solid blue",
    "background-color": "lightblue",
    "text-align": "center"
});

//personalisation du bouton pay with card de stripe
$(".stripe-button-el span").remove();
$("button.stripe-button-el").removeAttr('style').css({
    "background-image": "linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4)",
    "padding": "6px 12px",
    "color": "white",
    "font-size": "1em",
    "text-align": "center"
}).html("Procéder au paiement");

//personalisation de l'adresse courriel du client dans le modal stripe
$("span.Header-loggedInEmail").removeAttr('style').css({
    "color": "red",
    "font-weight": "bold"
});

// levée d'un message au click de la checkbox "tarif réduit"
$('input[type=checkbox]').click(function () {
    if (this.checked) {

        $(this).parent().append("<p class='info'><strong>Le tarif réduit ne peut s\'appliquer qu\'aux étudiants, militaires, personnels du musée." +
            " Un justificatif sera demandé lors de votre visite</strong></p>");
    } else {
        $(this).siblings('.info').remove();
    }

});

