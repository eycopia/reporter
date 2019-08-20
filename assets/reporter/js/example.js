$(document).ready(function() {

    PNotify.defaults.styling = 'bootstrap3'; // Bootstrap version 3
    PNotify.defaults.icons = 'bootstrap3'; // glyphicons

    notice = PNotify.notice({
        title: 'Confirmation Needed',
        text: 'Are you sure?',
        // icon: 'fas fa-question-circle',
        hide: false,
        autoDisplay: false,
        stack: {
            'dir1': 'down',
            'modal': true,
            'firstpos1': 25
        },
        buttons: [
            {
                text: 'Ok',
                textTrusted: false,
                addClass: '',
                primary: true,
                // Whether to trigger this button when the user hits enter in a single line
                // prompt. Also, focus the button if it is a modal prompt.
                promptTrigger: true,
                // click: (notice, value) => function() {
                //     notice.close();
                //     notice.fire('pnotify.confirm', {notice, value});
                // }
            },
            {
            text: 'Cancel',
            textTrusted: false,
            addClass: '',
            // click: (notice) => {
            //     notice.close();
            //     notice.fire('pnotify.cancel', {notice});
            // }
            }
    ],
        modules: {
            Confirm: {
                confirm: true
            },
            Buttons: {
                closer: true,
                sticker: false
            },
            History: {
                history: false
            },
        }
    });
    notice.on('pnotify.confirm', function() {
        alert('Ok, cool.');
    });
    notice.on('pnotify.cancel', function() {
        alert('Oh ok. Chicken, I see.');
    });

    $("table").on('click', "td a.exampleNotify", function(){
        notice.open();
    });

});
