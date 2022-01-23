jQuery(document).ready(function($) {
	function wcfmAjax(filter, resId) {
        var filter = $(filter);
        if( filter ) {
            $.ajax({
                url:filter.attr('action'),
                data:filter.serialize(), // form data
                type:filter.attr('method'), // POST
                cache: false,
                beforeSend: function() {
                    $('.loader').show();
                },
                complete: function(){
                    $('.loader').hide();
                },
                success:function(data){
           			$(resId).html(data);
                    setTimeout(function() {
                        $('#ms-settings-form')[0].reset();
                        location.reload();
                    }, 2000);
                },
                async: "false",
            });
        }
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function checkIfEmpty() {
        $('#ms-settings-form input[type="text"]').each(function () {
            if (!$(this).val() ) {
                $(this).addClass('error');
                $('.invalid-text').text('Please Fill up this field!');
            }
            else {
                $(this).removeClass('error');
                $('.invalid-text').empty();
            }
        });

        $('.email-format').each(function() {
            if( !isEmail( $(this).val() ) ) {
                $(this).addClass('error email-error');
                $('.invalid-email').text('Invalid Email');
            }
            else {
                $(this).removeClass('error email-error');
                $('.invalid-email').empty();
            }
        });
    }

     $('#ms-settings-form input[type="text"]').keyup(function () {
        if (!$(this).val() ) {
            $(this).addClass('error');
            $('.invalid-text').text('Please Fill up this field!');
        }
        else {
            $(this).removeClass('error');
            $('.invalid-text').empty();
        }
    });


    $('#ms-settings-form').submit(function(){
		var resId = '#result';
		wcfmAjax(this, resId );
		return false;
    });

    $('.email-format').keyup(function() {
        if( !isEmail( $(this).val() ) ) {
            $(this).addClass('error email-error');
            $('.invalid-email').show();
            $('.invalid-email').text('Invalid Email');
        }
        else {
            $(this).removeClass('error email-error');
            $('.invalid-email').empty();
        }
    });


    $('#add-store-button').click(function(e) {
        e.preventDefault();
        checkIfEmpty();
        var countError = $('.error').length;
        console.log(countError);
        if( countError == 0 ) {
            $('#ms-settings-form').submit();
        }
    });


});	