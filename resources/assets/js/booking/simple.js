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

    function updateperseons() {
        let roomCount = parseInt(jQuery('.second-block.rme-det .inner-block .right .No .text').text());
        let personCount = parseInt(jQuery('.second-block.tra-det .inner-block .right .No .text').text());
        jQuery('.third-block .first-bl').each(function (index) {
            if (personCount > 0) {
                console.log(personCount);
                let personsForThisRoom = Math.min(2, Math.ceil(personCount / roomCount));
                jQuery(this).find('#bedding_configuration option').prop('disabled', false);
                jQuery(this).find('#bedding_configuration option').eq(personsForThisRoom - 1).prop('selected', true);
                jQuery(this).find('#bedding_configuration option').each(function (optIndex) {
                    if (optIndex !== personsForThisRoom - 1) {
                        jQuery(this).prop('disabled', true);
                    }
                });
                personCount -= personsForThisRoom;
                roomCount--;
            } else {
                jQuery(this).find('#bedding_configuration option').prop('disabled', true).prop('selected', false);
            }
        });
    }

    $(document).ajaxComplete(function () {
        console.log('ajaxcoml')
        setTimeout(() => {
            updateperseons();
        }, 5000);

    });

    function updateRoomNumbers(minRooms) {
        jQuery('.third-block .first-bl').each(function (index) {
            if (index < minRooms) {
                jQuery(this).find('h6:first').text('Room ' + (index + 1));
            } else {
                jQuery(this).closest('.first-bl').remove();
            }
        });
        updateperseons();


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
        if (text < 5) {
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
    /*let hover = jQuery('[data-action="hover"]');

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
    });*/

    //hover details
    jQuery(document).on('mouseover', '.second-block .hotel-details .information-hover', function () {
        jQuery('.accommodation-details-hover').css('display', 'none');
        jQuery('.information-hover').removeClass('hovor');
        jQuery(this).closest('.hotel-details').find('.information-hover').addClass('hovor');
        jQuery(this).closest('.hotel-details').find('.accommodation-details-hover').css('display', 'block');
    });

    jQuery(document).on('mouseleave', '.hotel-details', function () {
        jQuery(this).find('.information-hover').removeClass('hovor');
        jQuery(this).find('.accommodation-details-hover').css('display', 'none');
    });

    jQuery(document).on('mouseover', '.third-block .information-hover', function () {
        jQuery('.accommodation-details-hover').css('display', 'none');
        jQuery('.information-hover').removeClass('hovor');
        jQuery(this).closest('.inn').find('.information-hover').addClass('hovor');
        jQuery(this).closest('.inn').find('.accommodation-details-hover').css('display', 'block');
    });

    jQuery(document).on('mouseleave', '.third-block .inn', function () {
        jQuery(this).find('.information-hover').removeClass('hovor');
        jQuery(this).find('.accommodation-details-hover').css('display', 'none');
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

    jQuery('.see-more-popup .close-button').click(function(){
        jQuery('.see-more-popup').css('display', 'none');
    })

    jQuery('.mob-static-see-more').click(function() {
        jQuery('.static-mobile-description').toggle();
        jQuery('.price-details-block').toggle();
        jQuery('.total-block').toggle();
        
        var currentText = jQuery('.mob-static-see-more p:first').text();
        
        if (currentText === 'SEE MORE') {
            jQuery('.mob-static-see-more p:first').text('SEE LESS');
        } else {
            jQuery('.mob-static-see-more p:first').text('SEE MORE');
        }
    });

    /*$("#custom-input-date").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '1970:c',
        minDate: new Date(1970, 0, 1)
    });*/

    /* input scroll */


    jQuery('.submit-btn-cls .submit-btn').click(function () {

        var form = jQuery(this).closest('form');
        var error = false;

        jQuery(form).find('input:required').each(function () {
            if (jQuery(this).val().trim().length === 0) {
                error = true;
                jQuery('html, body').animate({
                    scrollTop: form.offset().top
                }, 500);
                return false;
            }
        });

    });

    jQuery('.second-form .left-col .evnt-name .head-evnt').text(jQuery('.second-form .right-col .snd-sec .right-col h4:first').text());

    jQuery('.lead-purchase-traveller-block input[type="checkbox"]').click(function() {
        jQuery('.lead-purchase-traveller-block input[type="checkbox"]').prop('checked', false);
        jQuery(this).prop('checked', true);
        if (jQuery(this).val() == 'Yes') {
            jQuery('.purchase-info-block').hide();
            jQuery('.lead-passenger').hide();
        } else {
            jQuery('.purchase-info-block').show();
            jQuery('.lead-passenger').show();
        }
    });
    
    var checkedValue = jQuery('.lead-purchase-traveller-block input[type="checkbox"]:checked').val();
    if (checkedValue == 'Yes') {
        jQuery('.lead-passenger').hide();
        jQuery('.purchase-info-block').hide();
    } else {
        jQuery('.purchase-info-block').show();
        jQuery('.lead-passenger').show();
    }

});

document.addEventListener('livewire:load', function () {
            
    Livewire.hook('message.processed', (message, component) => {
        if (Object.keys(component.serverMemo.errors).length > 0) {
            const firstErrorElement = document.querySelector('.error-label');
            if (firstErrorElement) {
                const elementPosition = firstErrorElement.getBoundingClientRect().top + window.scrollY;
                const offsetPosition = elementPosition - 70;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }
    });
    var checkedValue = jQuery('.lead-purchase-traveller-block input[type="checkbox"]:checked').val();
    if (checkedValue == 'Yes') {
        jQuery('.lead-passenger').hide();
    } else {
        jQuery('.lead-passenger').show();
    }
});


