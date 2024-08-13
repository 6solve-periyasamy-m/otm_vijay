jQuery(document).ready(function () {

    // Close popup on click
    jQuery('#liveToast .btn-close').click(function () {
        jQuery('#liveToast').hide();
    })

    //hover details
    jQuery(document).on('click', function (event) {
        return;
        jQuery('.accommodation-details-hover').css('display', 'none');
        jQuery('.hotel-details .information-hover').removeClass('hovor');
        if (!jQuery(event.target).closest('.second-block, .third-block').length) {
            jQuery('.second-block .hotel-details .information-hover').removeClass('hovor');
            jQuery('.second-block .accommodation-details-hover').css('display', 'none');
            jQuery('.third-block .information-hover').removeClass('hovor');
            jQuery('.third-block .inn .accommodation-details-hover').css('display', 'none');
        }
    });

    // ACTIONS
    document.emojiSource = './tam-emoji/img/';

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
        let sele = jQuery('.second-block.tra-det .inner-block .right .No .text').text();
        sele = parseInt(sele);

        if(sele % 2 == 0){
            jQuery('.third-block .first-bl:last .travellers-select option:eq(1)').prop('selected', true).prop('disabled', false);
            jQuery('.third-block .first-bl:last .travellers-select option:eq(0)').prop('disabled', true);
        } else {
            jQuery('.third-block .first-bl:last .travellers-select option:eq(0)').prop('selected', true).prop('disabled', false);
            jQuery('.third-block .first-bl:last .travellers-select option:eq(1)').prop('disabled', true);
        }

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

    //check box right colm
    jQuery('.right-col .third-col .additional-block .form-field-checkbox').click(function () {
        jQuery('.right-col .third-col .additional-block .form-field-checkbox .left-ass input').prop('checked', false);

        jQuery(this).find('input').prop('checked', true);
    });
    jQuery('.right-col .third-col .additional-block .card-field-box .first-in').click(function () {
        jQuery('.right-col .third-col .additional-block .card-field-box .first-in').removeClass('active');
        jQuery(this).addClass('active');
    })

    window.setupPhoneField = function (input) {
        return window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: callback => {
                fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("us"));
            },
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    }

    jQuery('[data-action="popup"]').click(function (event) {
        event.preventDefault();
        jQuery('.' + jQuery(event.target).attr('data-target')).css('display', 'block');
    });

    jQuery('[data-action="close"]').click(function (event) {
        event.preventDefault();
        jQuery(event.target).closest('[data-role="closeable"]').css('display', 'none');
    });


    // TODO: Debug hover sometimes not finding the target
    let hover = jQuery('[data-action="hover"]');

    hover.on('mouseover', function (event) {
        let target = jQuery('.' + jQuery(event.target).attr('data-target'));
        target.css('display', 'block');
        target.addClass('hovor');
    });

    hover.on('mouseleave', function (event) {
        let target = jQuery('.' + jQuery(event.target).attr('data-target'));
        if (!target.hasClass('persist')) {
            console.log('not persisting');
            console.log(target);
            target.css('display', 'none');
            target.removeClass('hovor');
        }
    });

    hover.on('click', function (event) {
        event.preventDefault();
        let target = jQuery('.' + jQuery(event.target).attr('data-target'));
        if (target.hasClass('persist')) {
            target.removeClass('persist');
            target.removeClass('hovor');
            target.css('display', 'none');
        } else {
            target.addClass('persist');
            target.addClass('hovor');
            target.css('display', 'block');
        }
    });
    /* Popup Close */
    $(document).on('click', function (event) {
        var target = $(event.target);
        if (!target.closest('.upgrades-popup .convco').length && !target.hasClass('upgrade-cls')) {
          $('.upgrades-popup').css('display', 'none');
        }
        if (!target.closest('.see-more-popup .convco-two').length && !target.hasClass('seemore-href')) {
            $('.see-more-popup').css('display', 'none');
        }
    });

    /* input scroll */


    jQuery('.submit-btn-cls .submit-btn').click(function() {
        function isGmail(email) {
            return /^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(email);
        }
        var form = jQuery(this).closest('form');
        var error = false;
        
        jQuery(form).find('input:required').each(function() {
            if (jQuery(this).attr('type') == 'email') {
                var email = jQuery(this).val().trim();
                if (!isGmail(email)) {
                    error = true;
                    jQuery('html, body').animate({
                        scrollTop: form.offset().top
                    }, 500);
                    return false; 
                }
            }
            if (jQuery(this).val().trim().length === 0) {
                error = true;
                jQuery('html, body').animate({
                    scrollTop: form.offset().top
                }, 500);
                return false; 
            }
        });

    });

});
