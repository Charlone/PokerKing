// on load show home
$(document).ready(function () {
    $('.game-home').show(2000)
})

// nav menu click events
$('#home-link').click(() => {
    handleViews('.game-home');
})

$('#stats-link').click(() => {
    handleViews('.game-stats');
})

$('#profile-link').click(() => {
    handleViews('.game-profile');
})

$('#reset-link').click(() => {
    handleViews('.game-reset');
})

$('.reset-button').click(() => {
    callApi($('#reset-link').attr('data-href'));
})

$('.alert-button').click(() => {
    $('.alert-box').hide();
})

$('.play-button').click(() => {
    gameStartAlert();
    handleViews('.game-play');
})

// functions
const initialiseViews = () => {
    const views = ['.game-home', '.game-stats', '.game-profile', '.game-reset', '.game-play'];
    views.forEach((view) => {
        $(view).hide();
    })
}

export const handleViews = (element) => {
    initialiseViews();

    backgroundSwitcher(element);

    $(element).show(2000);

    switch (element) {
        case '.game-stats': callApi($('#stats-link').attr('data-href')); break;
        case '.game-profile': callApi($('#profile-link').attr('data-href')); break;
    }
}

const callApi = (url) => {
    const user = $('#js-init-info').attr('data-user-id');

    $.ajax({
        url: `${url}?player_id=${user}`,
    }).then((data) => {
        if (url.includes('stats')) {
            statsHandler(data['start_balance'], data['hands_played'], data['total_bet_amount'], data['outcome'], data['current_balance']);
        } else if (url.includes('profile')) {
            profileHandler(data['first_name'], data['last_name'], data['email'], data['street'], data['city'], data['country']);
        } else if (url.includes('reset')) {
            if (data['delete']) {
                resetHandler('text-success', 'Game has been reinitialised!', 'btn-success', 'alert-success', 3000);
                handleViews('.game-stats');
            } else {
                resetHandler('text-danger', 'Something went wrong.', 'btn-danger', 'alert-danger', 2000);
            }
        }
    })
}

const backgroundSwitcher = (element) => {
    let background = $('.game-wrapper');

    switch (element) {
        case '.game-stats':
            background.css('background-image', 'url(/build/images/components/chips_stats.png)');
            break;
        case '.game-profile':
            background.css('background-image', 'url(/build/images/components/profile.png)');
            break;
        case '.game-reset':
            background.css('background-image', 'url(/build/images/components/reset.png)');
            break;
        case '.game-home':
            background.css('background-image', 'url(/build/images/components/las_vegas.png)');
            break;
        case '.game-play':
            background.css('background-image', 'url(/build/images/components/table_cloth.png)');
            break;
        default:
            background.css('background-image', 'url("/build/images/components/las_vegas.png")');
            break;
    }
}

const statsHandler = (startBalance, handsPlayed, totalBetAmount, outcome, currentBalance) => {
    $('table > tbody > tr > td.start-balance').text('€' + startBalance);
    $('table > tbody > tr > td.hands-played').text(handsPlayed);
    $('table > tbody > tr > td.bet-amount').text('€' + totalBetAmount);
    $('table > tbody > tr > td.outcome').text('€' + outcome);
    $('table > tbody > tr > td.current-balance').text('€' + currentBalance);
}

const profileHandler = (firstName, lastName, email, street, city, country) => {
    $('.fullname').text(`Full name: ${firstName} ${lastName}`);
    $('.email').text(`E-mail: ${email}`);
    $('.address').text(`Address: ${street},`);
    $('.city').text(city).css('margin-left', '85px');
    $('.country').text(country).css('margin-left', '85px');
}

const resetHandler = (textClass,text,buttonClass,alertClass,time) => {
    $('.alert-text').addClass(textClass).text(text);
    $('.alert-button').addClass(buttonClass);
    $('.alert-box').addClass(alertClass).hide().removeClass('d-none').show(time);
}

const gameStartAlert = () => {
    $('.alert-text-2').addClass('text-success');
    $('.alert-button-2').addClass('btn-success');
    $('.game-start-alert').addClass('alert-success').hide().removeClass('d-none').show(3000);
}
