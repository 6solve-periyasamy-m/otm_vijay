jQuery(document).ready(function () {
    jQuery('.upgrade-cls').click(function () {
        jQuery(".accom-popup-overlay-in").css("display", "block");
    });
    jQuery('#liveToast .btn-close').click(function () {
        jQuery('#liveToast').hide();
    })
    jQuery('.accom-popup-overlay-in .convco .close-btn').click(function () {
        jQuery(".accom-popup-overlay-in").css("display", "none");
    });

    jQuery('.first-form').submit(function (e) {
        e.preventDefault();
        jQuery('.first-form').hide();
        jQuery('.top-nav-sec .head h1').text('Checkout');
        jQuery('.second-form').css('display', 'flex');
        jQuery('.second-form .left-col .top-check-top-cls').trigger('click');
    });

    $("#custom-input-date").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:c',
        minDate: new Date(1900, 0, 1)
    });


    //hover details
    jQuery(document).on('mouseover', '.first-form .second-block .marl', function () {
        jQuery('.ov-block-on-cl').css('display', 'none');
        jQuery('.marl').removeClass('hovor');
        jQuery(this).closest('.second-block ').find('.mkvk-wh-bl .marl').addClass('hovor');
        jQuery(this).closest('.second-block ').find('.ov-block-on-cl').css('display', 'block');
    });

    jQuery(document).on('mouseleave', '.first-form .second-block', function () {
        jQuery(this).find('.mkvk-wh-bl .marl').removeClass('hovor');
        jQuery(this).find('.ov-block-on-cl').css('display', 'none');
    });

    jQuery(document).on('mouseover', '.first-form .third-block .first-bl .marl', function () {
        jQuery('.ov-block-on-cl').css('display', 'none');
        jQuery('.marl').removeClass('hovor');
        jQuery(this).closest('.first-bl').find('.ov-block-on-cl').css('display', 'block');
        jQuery(this).addClass('hovor');
    });

    jQuery(document).on('mouseleave', '.first-form .third-block .first-bl', function () {
        jQuery(this).find('.marl').removeClass('hovor');
        jQuery(this).find('.ov-block-on-cl').css('display', 'none');
    });


    jQuery(document).on('click', function (event) {
        jQuery('.ov-block-on-cl').css('display', 'none');
        jQuery('.mkvk-wh-bl .marl').removeClass('hovor');
        if (!jQuery(event.target).closest('.second-block, .third-block').length) {
            jQuery('.second-block .mkvk-wh-bl .marl').removeClass('hovor');
            jQuery('.second-block .ov-block-on-cl').css('display', 'none');
            jQuery('.third-block .marl').removeClass('hovor');
            jQuery('.third-block .inn .ov-block-on-cl').css('display', 'none');
        }
    });

    //get data from prev form
    jQuery('.second-form .left-col .top-check-top-cls').click(function () {
        if (jQuery('.contain-vv input').is(':checked')) {
            var firstform = jQuery('.first-form .top-form-contain');
            var secondform = jQuery('.second-form .top-form-contain');
            jQuery(secondform).find('#first_name').val(jQuery(firstform).find('#first_name').val());
            jQuery(secondform).find('#last_name').val(jQuery(firstform).find('#last_name').val());
            jQuery(secondform).find('#email').val(jQuery(firstform).find('#email').val());
            jQuery(secondform).find('#mobile_number').val(jQuery(firstform).find('#mobile_number').val());

            var firstFormDialCode = jQuery(firstform).find('.mobile_field ul li.iti__active').data('dial-code');
            var selector = jQuery(secondform).find('.mobile_field ul li[data-dial-code="' + firstFormDialCode + '"]');
            jQuery(secondform).find('.mobile_field .iti__selected-dial-code').text(jQuery(firstform).find('.mobile_field .iti__selected-dial-code').text())


            var flag = jQuery(secondform).find('.iti__flag');
            flag.removeClass();
            flag.addClass('iti__flag');
            var flag1 = jQuery(firstform).find('.iti__flag');
            jQuery(flag1).removeClass('iti__flag');
            var otherClasses = flag1.attr('class')
            flag.addClass(otherClasses);
            jQuery(flag1).addClass('iti__flag')

            jQuery(selector).trigger('click');
            jQuery(selector).addClass('iti__highlight iti__active');

        } else {
            var secondform = jQuery('.second-form .top-form-contain');
            jQuery(secondform).find('#first_name').val('');
            jQuery(secondform).find('#last_name').val('');
            jQuery(secondform).find('#email').val('');
            jQuery(secondform).find('#mobile_number').val('');
            jQuery(secondform).find('.mobile_field .iti__selected-dial-code').text('+61');
            var flag = jQuery(secondform).find('.iti__flag');
            flag.removeClass();
            flag.addClass('iti__flag');
            flag.addClass('iti__au')

        }
    });


    // ACTIONS
    $("input").on("change", function (e) {
        $(this).siblings(".label-error").text("");
        $(this).removeClass("error");
    })

    $("#custom-input-date").on("focusout", function (e) {
        if ($(this).val() != '') {
            dateValidation($(this));
        }
    })

    // CHECK
    function dateValidation(input) {
        var errorLabel = input.siblings(".label-error");
        var date = input.val();

        input.removeClass("error");
        errorLabel.text("");

        var matches = /^(\d{1,2})[/\/](\d{1,2})[/\/](\d{4})$/.exec(date);

        if (matches == null) {
            input.addClass("error");
            errorLabel.text("Date not valid.");
        };

        var d = matches[1];
        var m = matches[2] - 1;
        var y = matches[3];
        var composedDate = new Date(y, m, d);

        if (composedDate.getDate() == d && composedDate.getMonth() == m && composedDate.getFullYear() == y) {
        } else {
            input.addClass("error");
            errorLabel.text("Date not valid.");
        }
    }


    document.emojiSource = './tam-emoji/img/';

    $('#summernote').summernote({
        placeholder: 'Message',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['font', ['bold', 'italic', 'underline']],
            //   ['para', ['paragraph']],
            ['para', ['paragraph', 'ol']],
            ['insert', ['link', 'picture', 'emoji']],
        ]
    });

    //back to form
    jQuery('.top-nav-sec .navi').click(function () {
        jQuery('.ma-block .first-form').css('display', 'flex');
        jQuery('.ma-block .second-form').css('display', 'none');
        jQuery('.top-nav-sec .head h1').text('Request to book');

    })
    //add and minus the count
    /*var textcont =  parseInt(jQuery('.rme-det .inner-block .right .inn').find('.No .text').text());
    for (var i = 0; i < textcont; i++) {
        if(i==0){
            continue
        }
        var newRoom = jQuery('.third-block .first-bl:first').clone();
        clearSelectedValues(newRoom);
        newRoom.appendTo('.third-block');
    }*/
    //dynamic rooms

    let minRooms = 1;
    let maxRooms = 1;

    function updateRoomNumbers(minRooms) {
        jQuery('.third-block .first-bl').each(function (index) {
            if (index < minRooms) {
                jQuery(this).find('h6:first').text('Room ' + (index + 1));
            } else {
                jQuery(this).closest('.first-bl').remove();
            }
        });

//         jQuery('.third-block .first-bl').each(function(index) {
//             jQuery(this).find('h6:first').text('Room ' + (index + 1));
//         });
    }

    function clearSelectedValues(clonedBlock) {
        clonedBlock.find('select').each(function () {
            jQuery(this).val('');
        });
    }

    function updateRooms(people) {
        switch (people) {
            case 1:
                minRooms = maxRooms = 1;
                break;
            case 2:
                minRooms = 1;
                maxRooms = 2;
                break;
            case 3:
                minRooms = 2;
                maxRooms = 3;
                break;
            case 4:
                minRooms = 2;
                maxRooms = 4;
                break;
            case 5:
            case 6:
                minRooms = 3;
                maxRooms = 5;
                break;
            case 7:
                minRooms = maxRooms = 4;
                break;
            default:
                minRooms = 1;
                maxRooms = 1;
        }

        let roomCount = jQuery('.third-block .first-bl').length;

        while (roomCount > maxRooms) {
            jQuery('.third-block .first-bl:last').remove();
            roomCount--;
        }

        while (roomCount < minRooms) {
            let newRoom = jQuery('.third-block .first-bl:first').clone();
            clearSelectedValues(newRoom);
            newRoom.appendTo('.third-block');
            roomCount++;
        }

        updateRoomNumbers(minRooms);

        jQuery('.rme-det .inner-block .right .inn .No .text').text(minRooms);
    }

    jQuery('.tra-det .inner-block .right .inn .Min').click(function () {
        let textval = jQuery(this).closest('.inn').find('.No .text');
        let text = parseInt(textval.text());

        if (text > 1) {
            textval.text(text - 1);
            updateRooms(text - 1);

            jQuery('.rme-det .inner-block .right .inn .No .text').text(minRooms);

            let roomCount = jQuery('.third-block .first-bl').length;

            while (roomCount > minRooms) {
                jQuery('.third-block .first-bl:last').remove();
                roomCount--;
            }

            while (roomCount < minRooms) {
                let newRoom = jQuery('.third-block .first-bl:first').clone();
                clearSelectedValues(newRoom);
                newRoom.appendTo('.third-block');
                roomCount++;
            }

            updateRoomNumbers(minRooms);
        }
    });

    jQuery('.tra-det .inner-block .right .inn .Max').click(function () {
        let textval = jQuery(this).closest('.inn').find('.No .text');
        let text = parseInt(textval.text());
        if (text < 7) {
            textval.text(text + 1);
            updateRooms(text + 1);
        }
    });

    jQuery('.rme-det .inner-block .right .inn .Min').click(function () {
        let textval = jQuery(this).closest('.inn').find('.No .text');
        let text = parseInt(textval.text());

        if (text > minRooms) {
            textval.text(text - 1);
            let roomCount = jQuery('.third-block .first-bl').length;
            if (roomCount > 1) {
                jQuery('.third-block .first-bl:last').remove();
                updateRoomNumbers(text - 1);
            } else {
                jQuery('.third-block .first-bl:first').hide();
            }
        }
    });

    jQuery('.rme-det .inner-block .right .inn .Max').click(function () {
        let textval = jQuery(this).closest('.inn').find('.No .text');
        let text = parseInt(textval.text());

        if (text < maxRooms) {
            textval.text(text + 1);
            let newRoom = jQuery('.third-block .first-bl:first').clone();
            clearSelectedValues(newRoom);
            newRoom.appendTo('.third-block');
            updateRoomNumbers(text + 1);
        }
    });

    //check box right colm
    jQuery('.right-col .third-col .additional-block .form-field-checkbox').click(function () {
        jQuery('.right-col .third-col .additional-block .form-field-checkbox .left-ass input').prop('checked', false);

        jQuery(this).find('input').prop('checked', true);
    });
    jQuery('.right-col .third-col .additional-block .card-field-box .first-in').click(function () {
        jQuery('.right-col .third-col .additional-block .card-field-box .first-in').removeClass('active');
        jQuery(this).addClass('active');
    })

    jQuery('p.see-more a').click(function (e) {
        e.preventDefault();
        jQuery('.accom-popup-overlay-in-two').css('display', 'block');
    });
    jQuery('.accom-popup-overlay-in-two .close-btn').click(function () {
        jQuery('.accom-popup-overlay-in-two').css('display', 'none');
    })
    const inputs = document.querySelectorAll("#mobile_number");
    inputs.forEach(input => {
        window.intlTelInput(input, {
            initialCountry: "au",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    });
    jQuery('.mobile_field .iti__preferred').remove();
    jQuery('.mobile_field .iti__divider').remove();
});
