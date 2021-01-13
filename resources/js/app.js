// Iport bootstarp e jquery
require('./bootstrap');
import 'bootstrap';
var $ = require('jquery');


$(document).ready(function() {

  $("#inserisci").on('click', function(e){

    // Prendo i dati da inviare alla chiamata ajax
    e.preventDefault();
    var id = $("input[name=id]").val();
    var data = $("input[name=data]").val();
    var descrizione = $("input[name=descrizione]").val();
    var ogni_anno = $('input[name="ogni_anno"]:checked').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $('.canc').remove();

    // Chiamata ajax per inserire un nuovo evento
    $.ajax({
      type:'POST',
      url:'/holidays',
      data:{_token:CSRF_TOKEN,
            id: id,
            data: data,
            descrizione: descrizione,
            ogni_anno: ogni_anno},
      success:function(data){
        $('.lista tr:first').after('<tr data-copia="'+ data.descrizione + "-" + data.data +
        '"><td class="data" data-giorno="'+ data.copia +'" >'
              + data.data + '</td><td class="descrizione "data-descrizione="'+ data.descrizione+'">'
              + data.descrizione +'</td><td class="anno" data-anno="'+ data.ogni_anno +'">'
              + data.ogni_anno +'</td><td class="rigaCopia">'
              + $('.rigaCopia').html() +'</td><td class="rigaModifica">'
              + $('.rigaModifica').html() +'</td><td class="rigaElimina">'
              + $('.rigaElimina').html() +'</td></tr>');
        $('.elimina').attr('action', 'http://127.0.0.1:8000/holidays/'+ data.id +'');
      },
      error: function() {
          alert('Error occured');
      }
    })

    // Resettare l'id
    $("input[name=id]").val(null);
    
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
        $('#startDate').css("background-color", "white");
        $('#endDate').attr("disabled", false);
        $('#endDate').css("background-color", "white");
        $('#anni_si').attr("disabled", false);
        $('#anni_no').attr("disabled", false);
      } else {
        $('#startDate').attr("disabled", true);
        $('#startDate').css("background-color", "lightgray");
        $('#endDate').attr("disabled", true);
        $('#endDate').css("background-color", "lightgray");
        $('#anni_si').attr("disabled", true);
        $('#anni_no').attr("disabled", true);
      }
  });
  $('#filtroEvento').on('change', function(){
      if(this.checked){
        $('#evento').attr("disabled", false);
        $('#evento').css("background-color", "white");
      } else {
        $('#evento').attr("disabled", true);
        $('#evento').css("background-color", "lightgray");
      }
  });

  // Funzione per copiare negli appunti
  $('.lista').on('click', ".copia", function() {
    var evento = $(this).parents('tr').data("copia");

    // Creo un input d'appoggio nascosto per inserire la stringa da copiare
    // e una volta copiata la elimino
    var tempItem = document.createElement('input');
    tempItem.setAttribute('type','text');
    tempItem.setAttribute('display','none');
    tempItem.setAttribute('value',evento);
    document.body.appendChild(tempItem); 
    tempItem.select();
    tempItem.setSelectionRange(0, 99999);   // Per mobile
    document.execCommand('Copy');

    tempItem.parentElement.removeChild(tempItem);
  });

  // Funzione per modificare l'evento
  $('.lista').on('click', ".modifica", function(e) {

    var id = $(this).parents('td').siblings('td.id').attr('data-id')
    var data = $(this).parents('td').siblings('td.data').attr('data-giorno');
    var descrizione = $(this).parents('td').siblings('td.descrizione').attr('data-descrizione');
    var anno = $(this).parents('td').siblings('td.anno').attr('data-anno');

    // Aggiungo alla riga da modificare una classe per cancellare successivamente
    $('.lista tr').removeClass('canc');
    $(this).parents('tr').addClass("canc");
    

    $("input[name=id]").val(id);
    $("input[name=data]").val(data);
    $("input[name=descrizione]").val(descrizione);
    if(anno == 1){
      $('#ogni_anno_1').attr("checked", true);
    } else {
      $('#ogni_anno_2').attr("checked", true);
    }

  });

});
