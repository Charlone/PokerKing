import { handleViews } from "./iframe";

// event handlers
$('.alert-button-2, .fold-button').click(() => {
    $('.game-start-alert').fadeOut('fast');
    callGameApi('/game/stats');
    callGameApi('/game/play/drawHand');
})

$('.bet-100, .bet-200, .bet-500').click((bet) => {
    showOpponentHand();
    gamePlay(bet);
})

$('.modal-play-button').click(() => {
    $('#game-result-modal, .win, .loss, .draw').fadeOut('fast');
    callGameApi('/game/stats');
    callGameApi('/game/play/drawHand');
})

$('.modal-quit-button').click(() => {
    $('#game-result-modal, .win, .loss, .draw').fadeOut('fast');
    handleViews('.game-home');
})

// functions
const gamePlay = (bet) => {
    const user = $('#js-init-info').attr('data-user-id');
    const betAmount = $(bet.currentTarget).attr('data-bet-amount');
    const playerHand = getCards('player');
    const opponentHand = getCards('opponent');

    $.ajax({
        url: `/game/play/result?player_id=${user}&bet_amount=${betAmount}&player_hand=${playerHand}&opponent_hand=${opponentHand}`,
    }).then((data) => {
        setTimeout(() => {
            $('#game-result-modal').show('slow');

            switch (data['winner']) {
                case 'Game ended in a draw':
                    $('.draw').css('display', 'flex').fadeIn('slow');
                    break;
                case 'Opponent':
                    $('.loss').css('display', 'flex').fadeIn('slow');
                    break;
                default:
                    $('.win-summary').text(`Bet €${data['bet_amount']} and wins €${data['win_amount']} with ${data['rank']}`);
                    $('.win').css('display', 'flex').fadeIn('slow');
                    break;
            }
        }, 3000);
    })
}

const callGameApi = (url) => {
    const user = $('#js-init-info').attr('data-user-id');

    if (url.includes('stats')) {
        $.ajax({
            url: `${url}?player_id=${user}`,
        }).then((data) => {
            $('.current-balance-box').text(`€${data['current_balance']}`);
            if (data < 1) {
                handleViews('.game-reset');
            }
        });
    } else if (url.includes('drawHand')) {
        $.ajax({
            url: url,
        }).then((data) => {
            for (const [key, value] of Object.entries(data)) {
                if (key.includes('player')) {
                    $(`.${key}`).attr('data-value', value).attr('src', `/build/images/cards/${value}.png`);
                } else {
                    $(`.${key}`).attr('data-value', value).attr('src', '/build/images/cards/red_back.png');
                }
            }
        })
    }
}

const getCards = (player) => {
    let cards = []

    for (let i = 1; i <= 5; i++) {
        cards.push($(`.${player}-card-${i}`).attr('data-value'));
    }

    return cards;
}

const showOpponentHand = () => {
    const cards = $('.opponent-cards > img');
    for (const [key, value] of Object.entries(cards)) {
        if (key !== 'length') {
            const card = $(value);
            card.attr('src', `/build/images/cards/${card.attr('data-value')}.png`);
        }
    }
}