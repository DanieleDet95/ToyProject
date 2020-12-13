// Iport bootstarp e jquery
require('./bootstrap');
import 'bootstrap';
var $ = require('jquery');


$(document).ready(function() {

  $("#inserisci").on('click', function(e){

    // Prendo i dati da inviare alla chiamata ajax
    e.preventDefault();
    var data = $("input[name=data]").val();
    var descrizione = $("input[name=descrizione]").val();
    var ogni_anno = $('input[name="ogni_anno"]:checked').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    // Chiamata ajax per inserire un nuovo evento
    $.ajax({
      type:'POST',
      url:'/holidays',
      data:{_token:CSRF_TOKEN,
            data:data,
            descrizione: descrizione,
            ogni_anno: ogni_anno},
      success:function(data){
        $('.lista tr:first').after('<tr><td>'
              + data.data +'</td><td>'
              + data.descrizione +'</td><td>'
              + data.ogni_anno +'</td><td>'
              + $('.rigaElimina').html() +'</td><td>'
              + $('.rigaCopia').html() +'</td></tr>');
      },
      error: function() {
          alert('Error occured');
      }
    });

  });

  // Faccio comparire il popup dopo 5 secondi dal caricamento della pagina
  window.onload = function(){
    setTimeout(function(){
      $(".popup").css("display", "block");
    }, 5000);
  };

  // Funzione per chiudere il popup
  $('.chiudi').on('click', function(){
    $('.popup').fadeOut();
  });

  // Funzione per disabilitare gli input
  $('#ogni_anno').on('change', function(){
      if(this.checked){
        $('#ogni_anno_1').attr("disabled", false);
        $('#ogni_anno_2').attr("disabled", false);
      } else {
        $('#ogni_anno_1').attr("disabled", true);
        $('#ogni_anno_2').attr("disabled", true);
      }
  });
  $('#filtroData').on('change', function(){
      if(this.checked){
        $('#startDate').attr("disabled", false);
        $('#endDate').attr("disabled", false);
        $('#anni_si').attr("disabled", false);
        $('#anni_no').attr("disabled", false);
      } else {
        $('#startDate').attr("disabled", true);
        $('#endDate').attr("disabled", true);
        $('#anni_si').attr("disabled", true);
        $('#anni_no').attr("disabled", true);
      }
  });
  $('#filtroEvento').on('change', function(){
      if(this.checked){
        $('#evento').attr("disabled", false);
      } else {
        $('#evento').attr("disabled", true);
      }
  });

  // // Chaimata ajax per ordinare la tabella
  // e.preventDefault();
  // $(document).on('click', '.ordina', function(){ 
    
  //    // Prendo i dati da inviare alla chiamata ajax
  //   e.preventDefault();
  //   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 

  //   $.ajax({  
  //     type:'POST',
  //     url:'/holidays/order',  
  //     data:{_token:CSRF_TOKEN},  
  //     success:function(data)  
  //     {   
  //       console.log(data);
  //       // $('.lista tr:first').after('<tr><td>'
  //       //     + data.data +'</td><td>'
  //       //     + data.descrizione +'</td><td>'
  //       //     + data.ogni_anno +'</td><td>'
  //       //     + $('.rigaElimina').html() +'</td><td>'
  //       //     + $('.rigaCopia').html() +'</td></tr>'); 
  //     }  
  //   })  
  // }); 

  // Funzione per copiare negli appunti
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
    var descrizione = evento+' - '+giorno+' '+mese+' '+anno;

    // Creo un input d'appoggio nascosto per inserire la stringa da copiare
    // e una volta copiata la elimino
    var tempItem = document.createElement('input');
    tempItem.setAttribute('type','text');
    tempItem.setAttribute('display','none');
    tempItem.setAttribute('value',descrizione);
    document.body.appendChild(tempItem); 
    tempItem.select();
    tempItem.setSelectionRange(0, 99999);   // Per mobile
    document.execCommand('Copy');

    tempItem.parentElement.removeChild(tempItem);
  });
});
