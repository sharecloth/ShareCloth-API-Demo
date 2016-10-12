$(function() {

    var email, password;

    var $email = $('input[name=email]');
    var $password = $('input[name=password]');
    var $lists = $('#lists');
    var $getAvatarList =$('#get-avatar-list');
    var $getClothList = $('#get-cloth-list');


    function initPage()
    {
        email = $email.val();
        password = $password.val();

        $('.login-error').addClass('hidden');
    }


    $('#get-token-button').on('click', function(e) {
        e.preventDefault();

        initPage();

        $.ajax({
            'url' : 'api.php?method=login',
            'method': 'POST',
            'dataType': 'json',
            'data' : {
                "email": email,
                "password" : password
            },
            'success' : function(r) {
                if (r.result) {
                    $('.login-result').removeClass('hidden');
                    $('.login-result strong').text(r.apiSecret);

                    $lists.removeClass('hidden');
                } else {
                    $('.login-error').removeClass('hidden');
                }

            }
        })
    });


    $getAvatarList.on('click', function(e) {
        e.preventDefault();
        $.ajax({
            'url': 'api.php?method=avatarList',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                "email": email,
                "password" : password
            },
            'success': function(r) {
                if (r.result) {
                    $('.avatar-table-wrapper').html(r.html);
                }
            }
        });
    });

    $getClothList.on('click', function(e) {
        e.preventDefault();
        $.ajax({
            'url': 'api.php?method=itemList',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                "email": email,
                "password" : password,
                "list" : "all"
            },
            'success': function(r) {
                if (r.result) {
                    $('.cloth-table-wrapper').html(r.html);
                }
            }
        });
    });
});