export default class Support
{
    static init()
    {
        this.watchTicketPopupSubmit();
        this.watchOpenAndClose();
    }

    static watchOpenAndClose()
    {
        const $supPop = $('.sup-pop');

        // open
        $('.btn-OpenSupportPopup').on('click', event => {
            $supPop.addClass('on');
            $('.h-user-in, .h-box-user').removeClass('on');

        });

        // Detect clicking outside to close it
        $(document).on('click', event => {
            const container = $('.sup-pop.on, .btn-OpenSupportPopup');

            if (!container.is(event.target) && container.has(event.target).length === 0) {
                $supPop.removeClass('on');
            }
        });
    }

    static watchTicketPopupSubmit()
    {
        const $btn = $('.btn-createSupportTicket');

        $btn.on('click', event => {
            $btn.prop('disabled', true).text('Creating ticket ...');

            $.ajax({
                url: '/issues/create',
                data: $('.SupportTicketForm').serialize(),
                success: response => {
                    const $ui = $('.SupportTicketResponse');

                    if (response.error) {
                        return $ui.html(`
                            <br>
                            <div class="alert alert-error">
                                Sorry! Your ticket could not be created, reason: ${response.error}
                            </div>
                        `);
                    }

                    $ui.html(`
                        <br>
                        <div class="alert alert-success alert-success-pop">
                            Your ticket has been created! Ref: <strong>${response.ref}</strong><br>
                            - <a href="/issues/${response.id}">Click here to view your ticket</a>
                        </div>
                    `);
                }
            });
        });
    }
}
