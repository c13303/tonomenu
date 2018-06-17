/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {

    $('.save_ingredient').html("");
    $('.detail_menu').hide();

    $('.chosen-search-input').on('change', function () {
        var terms = $('.chosen-search-input').val();
        $('#save_ing_container').val(terms);
    });
    
    $('.show_detail').on('click',function(){
        var target = $(this).attr('data-show');
        
        if($('#' + target).is(":visible"))
        {
            $('#' + target).hide();
        }
        else
        {
            $('#' + target).show();
        }
        
    });


    /* save ingredient */

    $(document.body).on('click','#save_ingredient_button', function () {

        var url = $('#save_ing_route').val();


        var term = $("#save_ing_container").val();
        var selected = $("#tonobundle_recette_Ingredients").chosen().val();
        var json = JSON.stringify(selected);
        if (!term)
        {
            console.log('ERROR NO TERM');
        } else
        {
            console.log(term);
            console.log(url);

            data = {
                term: term,
                selected: json
            };


            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    console.log('saved ' + $('#save_ing_container').val());
                   ;
                    $('#tonobundle_recette_Ingredients').html(data.list);

                    $('#tonobundle_recette_Ingredients').trigger("chosen:updated");

                }
            })
        }

    });
    
    
    /* update ingredient type */
    $('.trigtype').on('click',function(){
        var url = $(this).attr('data-path');
        var id = $(this).attr('data-ingredient');
        var moi = $(this);
        $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    console.log('saved');
                   $('a[data-ingredient="'+id+'"]').removeClass('selected');
                   moi.addClass('selected');
                   

                }
            })
    });
    
    $('.changemeal').on('click',function(){
        $('#overlay').show();
        $('#recettereplacer').attr('data-day',$(this).attr('data-day'));
        $('#recettereplacer').attr('data-route',$(this).attr('data-route'));
        $('#recettereplacer').attr('data-mealid',$(this).attr('data-mealid'));        
    });
    
    
    $('.delmeal').on('click',function(){
        var route = $(this).attr('data-route');
        var mealid = $(this).attr('data-mealid');
        $('#mealid-'+mealid).html('');
        console.log(route);
        $.ajax({
                type: "POST",
                url: route,
                
                success: function (data) {
                    console.log( 'success del ' + data);      
                   

                },
                error:function(data)
                {
                    console.log(data);
                }
            })      
    });
    
    $('.changerlourdeur').on('click',function(){
        var route = $(this).attr('data-route');
        console.log(route);
        $.ajax({
                type: "POST",
                url: route,
                
                success: function (data) {
                    var id=data.id;
                    var lourdeur = data.lourdeur;
                    console.log('success ' + data)
                    $('#lourdeur_indicator_'+id).html(lourdeur);
                    
                    
                }
        });
    });
    
    
    $('.menufier').on('click',function(){
        var recette = $('#recettereplacer').val();
        var route = $('#recettereplacer').attr('data-route');
        var data = { recette : recette};
        console.log(route);
        $.ajax({
                type: "POST",
                url: route,
                data : data,
                
                success: function (data) {
                    console.log( data);      
                     $('#overlay').hide();
                     var id = $('#recettereplacer').attr('data-mealid');
                     console.log('update' + id);
                     $('#mealid-' + id).html(data.name);
                     if(data.deja)
                     {
                        $('#mealid-' + data.deja).html('');
  
                     }


                },
                error:function(data)
                {
                    console.log(data);
                }
            })
        
    });
    
    $('.overlayclose').click(function(){
        $('#overlay').hide();
    });
    
    
     
});