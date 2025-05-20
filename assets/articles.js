import { DataType } from 'ajv/dist/compile/validate/dataType';
import $ from 'jquery';

$(document).ready(function(){

    const $commentForm = $('#comment-form');
    const $commentsList = $('#comments-list');
    const $commentsCount = $('#comments-count');

    $commentForm.on('submit', function(e){
        e.preventDefault();

        const $submitBtn = $commentForm.find('button[type="submit"]');
        const originalBtnText = $submitBtn.html();

        $submitBtn.html('Envoi en cours...').prop('disable', true);

        $ajax({
            url: $commentForm.attr('action'),
            methode : 'POST',
            data: $commentForm.serialize(),
            dataType:'json',
            success: function(response){
                if (response.success){
                    $commentsList.prepend(response.commentHtml);
                    $commentsCount.text(response.$commentsCount);
                    $commentForm[0].reset();
                    showAlert('success', 'Votre commentaire a été publié  avec succès !');
                }else{
                    showAlert('danger', response.error || 'une erreur est survenue lors de l\'envoi du commentaire.');
                }
            },
            error: function(){
                showAlert('danger', 'Une erreur est survenue lors de l\'envoi du commentaire');
            },
            complete: function(){
                $submitBtn.html(originalBtnText).prop('disable', false);
            }
        })
    })
})