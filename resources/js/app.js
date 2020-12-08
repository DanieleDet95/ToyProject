require('./bootstrap');
var $ = require('jquery');

$(document).ready(function() {

  // Funzione per chiusere il popup
  $('.chiudi').on('click', function(){
    $('.popup').fadeOut();
  });

  // Funzione per disabilitare gli input
  $('#filtroData').on('change', function(){
      if(this.checked){
        $('#startDate').attr("disabled", true);
        $('#endDate').attr("disabled", true);
        $('#anni_si').attr("disabled", true);
        $('#anni_no').attr("disabled", true);
      } else {
        $('#startDate').attr("disabled", false);
        $('#endDate').attr("disabled", false);
        $('#anni_si').attr("disabled", false);
        $('#anni_no').attr("disabled", false);
      }
  });
  $('#filtroEvento').on('change', function(){
      if(this.checked){
        $('#evento').attr("disabled", true);
      } else {
        $('#evento').attr("disabled", false);
      }
  });

  // funzione per copiare negli appunti
  $('.copia').on('click', function(){
    var giorno = $(this).siblings(".giorno").val();
    var mese = $(this).siblings(".mese").val();
    var anno = $(this).siblings(".anno").val();
    var evento = $(this).siblings(".evento").val();
    if (mese == 1) {
      mese = 'Gennaio';
    } else if(mese == 2) {
      mese = 'Febbraio';
    }else if(mese == 3) {
      mese = 'Marzo';
    }else if(mese == 4) {
      mese = 'Aprile';
    }else if(mese == 5) {
      mese = 'Maggio';
    }else if(mese == 6) {
      mese = 'Giugno';
    }else if(mese == 7) {
      mese = 'Luglio';
    }else if(mese == 8) {
      mese = 'Agosto';
    }else if(mese == 9) {
      mese = 'Settembre';
    }else if(mese == 10) {
      mese = 'ottobre';
    }else if(mese == 11) {
      mese = 'Novembre';
    }else if(mese == 12) {
      mese = 'Dicembre';
    }
    var descrizione = evento+'-'+giorno+' '+mese+' '+anno;
    window.prompt("Copy to clipboard: Ctrl+C, Enter", descrizione);
    
    // descrizione.select();
    // descrizione.setSelectionRange(0, 99999);
    // document.execCommand("copy");

  });
});
