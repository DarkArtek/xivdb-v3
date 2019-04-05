import ButtonLoader from './ButtonLoader';

const AccountSettings =
{
    urls: {
        add:        '/account/characters/add',
        confirm:    '/account/characters/confirm'
    }
};
const AccountCharacterInstance =
{
    id: null,
};

export default class Account
{
    static init()
    {
        // character add submit
        $('.acc-add-char').on('submit', event =>{
            event.preventDefault();
            this.handleCharacterAdding();
        });

        // character verification
        $('html').on('click', '.acc-add-char-verify', event => {
            event.preventDefault();
            this.handleCharacterAddingVerification();
        })
    }

    static handleCharacterAdding()
    {
        const $form = $('.acc-add-char');
        const $view = $('.acc-add-char-res');
        ButtonLoader.on($form.find('button'), 'light');

        $.ajax({
            url: AccountSettings.urls.add,
            data: {
                name:   $(event.target).find('#name').val().trim(),
                server: $(event.target).find('#server').val().trim(),
            },
            success: response => {
                if (response.error) {
                    return $view.html(`
                        <br>
                        <div class="alert alert-danger">
                        ${response.error}
                        </div>
                    `);
                }

                // set instance
                AccountCharacterInstance.id = response.id;

                // set response
                $view.html(`
                    <hr>
                    <div class="acc-add-char-found">
                        <div>
                            <img src="${response.avatar}" height="80">
                        </div>
                        <div>
                            <h3>${response.name} &nbsp; <small>${response.server.toUpperCase()}</small></h3>
                            <div><small>#${response.id}</small></div>
                            <p>You now need to verify this character is yours. To do this, please enter your
                            <strong>character verification code</strong> onto your Lodestone profile. Once
                            you have done this, click <strong>Confirm Verification</strong>. You can remove
                            the code once verification is complete.</p>
                            <div class="acc-add-char-confirm">
                                <button class="btn btn-success acc-add-char-verify">Confirm Verification</button>
                                <div class="acc-add-char-confirm-res"></div>
                            </div>
                        </div>
                    </div>
                `);
            },
            error: (a,b,c) => {
                console.error(a,b,c);
                return $view.html(`
                    <div class="alert alert-danger">${c} - Please try again in a few minutes.</div>
                `);
            },
            complete: () => {
                ButtonLoader.off($form.find('button'));
            }
        });
    }

    static handleCharacterAddingVerification()
    {
        const $view = $('.acc-add-char-confirm-res');

        ButtonLoader.on($('.acc-add-char-verify'), 'light');

        $.ajax({
            url: AccountSettings.urls.confirm,
            data: {
                id: AccountCharacterInstance.id,
            },
            success: response => {
                if (response.error) {
                    return $view.html(`
                        <br>
                        <div class="alert alert-danger">
                        ${response.error}
                        </div>
                    `);
                }

                return $view.html(`
                    <br>
                    <div class="alert alert-success alert-success-pop">
                    <strong>${response.success}</strong>
                    </div>
                `);
            },
            error: (a,b,c) => {
                console.error(a,b,c);
                return $view.html(`
                    <div class="alert alert-danger">${c} - Please try again in a few minutes.</div>
                `);
            },
            complete: () =>{
                ButtonLoader.off($('.acc-add-char-verify'));
            }
        })
    }
}
