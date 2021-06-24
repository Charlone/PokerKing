$('.next-button').click(function () {
    validateForm(1);
})

$('.back-button').click(function () {
    registrationTabsHandler('prev');
})

$('#registration_submit').click(function (e) {
    e.preventDefault();
    if (validateForm(2) === true) {
        $('form[name=registration]').submit();
    }
})

function validateForm(form) {
    const ids = (form === 1) ?  $('.registration-form-group-1 .form-group input'):  $('.registration-form-group-2 .form-group input');
    const regFormError = $('.reg-form-error');
    let valid = true;
    let blank = 0;

    regFormError.text('');

    for (let i = 0; i < ids.length; i++) {
        const id = $(ids[i]).attr('id');
        const value = $(`#${id}`).val();
        let validation = getValidationRegexAndErrorMessage(id);

        if (value.length > 0) {
            if (validation.regex.test(value) === false) {
                regFormError.removeClass('d-none');
                regFormError.append(`${validation.label}: ${validation.description}<br>`);
                valid = false;
            }
        } else {
            blank++;
        }
    }

    if (blank > 0) {
        valid = false;
        regFormError.removeClass('d-none');
        regFormError.append(`Please fill in all details on form.<br>`);
    }

    if ($('#registration_password_first').val() !== $('#registration_password_second').val()) {
        valid = false;
        regFormError.append(`Passwords must match.<br>`);
    }

    switch (form) {
        case 1:
            if (valid === false) {
                return;
            } else {
                registrationTabsHandler(valid);
            }
            break;
        case 2:
            return valid;
    }
}

function getValidationRegexAndErrorMessage(element) {
    const elementLabel = element.replace('registration_','').replace('_', ' ').toUpperCase();
    let validation = {
        blank: `${elementLabel} cannot be empty.`,
        label: elementLabel,
    };

    switch (element) {
        case "registration_first_name":
        case "registration_last_name":
        case "registration_city":
        case "registration_country":
            validation['regex'] = /[a-zA-Z '.-]*[A-Za-z][^-<>]$/;
            validation['description'] = 'Only letters and spaces allowed';
            break;
        case "registration_username":
            validation['regex'] = /^[\w]*$/;
            validation['description'] = 'Only letters, numbers and underscores allowed.';
            break;
        case "registration_password_first":
        case "registration_password_second":
            validation['regex'] = /^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/;
            validation['description'] = 'Must contain 8-16 Chars, 1 Capital, 1 lowercase, 1 number, 1 non-alphanumeric.';
            break;
        case "registration_email":
            validation['regex'] = /^([a-zA-Z0-9_\-.]+)@([a-zA-Z0-9_\-.]+)\.([a-zA-Z]{2,5})$/gi;
            validation['description'] = 'Email format example@example.com';
            break;
        case "registration_street_address":
            validation['regex'] = /^[\w+ (\w+),-]{2,}$/;
            validation['description'] = 'Building name, number and street name.';
            break;
    }

    return validation;
}

function registrationTabsHandler(command) {
    const regFormError = $('.reg-form-error');
    if (command === true) {
        if (!regFormError.hasClass('d-none')) {
            regFormError.addClass('d-none');
        }
        $('.registration-form-group-1').addClass('hidden');
        $('.registration-form-group-2').removeClass('hidden');
    } else if (command === 'prev') {
        $('.reg-form-error').addClass('d-none');
        $('.registration-form-group-1').removeClass('hidden');
        $('.registration-form-group-2').addClass('hidden');
    }
}
