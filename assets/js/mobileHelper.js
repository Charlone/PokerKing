$(() => {
    if (self.innerWidth <= 425 || self.innerWidth <= 736 && self.innerHeight <= 425) {
        $('.welcome-register').text('Register');
        $('.welcome-login').text('Login');
        $('.registration-header').text('Register to play!')
    }
});