const token = $('meta[name="csrf-token"]').attr('content');

$(document).on('change', '.radio', function(){
    if($(this).val() == "yes") {
        $('.password-form').show();
    } else {
        $('.password-form').hide();
    }
});

$(document).ready(function(e){
    const routeUpdateStatus = $("#routeUpdateStatus").val();
    const routeRedirectToIndex = $("#routeRedirectToIndex").val();    
    const routeStoreUserModal = $("#routeStoreUserModal").val();  

    // only number in form
    $(".only-number").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });  

    // setup ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });          

    // click status
    $(".btn-status").on('click', function (e) {
        let id = $(this).data("id");
        let name = $(this).data("value");
        let status = $(this).data("status");
        let userId = $("#userId").val();
        let msg = '';
        let msg2 = '';
        let msg3 = '';
        let status_id = 0; // Status as NEW
        let route = routeUpdateStatus;
        let redirect = routeRedirectToIndex;

        if (status == "delete") {
            msg = "delete";
            msg2 = "Deletion";
            msg3 = "Deleted";
            status_id = 4; // Status as DELETED
        } else if (status == "active") {
            msg = "active";
            msg2 = "Activation";
            msg3 = "Activated";
            status_id = 1; // Status as ACTIVE
        } else if (status == "inactive") {
            msg = "inactive";
            msg2 = "Inactivation";
            msg3 = "Inactivated";
            status_id = 2; // Status as INACTIVE
        } else {
            msg = "onhold";
            msg2 = "Onhold";
            msg3 = "Onhold";
            status_id = 3; // Status as ONHOLD
        }

        softAction(id, name, route, userId, msg, msg2, msg3, status_id, redirect);
    });

    // show modal
    $(".btn-modal").on('click', function (e) {
        e.preventDefault();

        $('.clear-input').val('');

        $('#userModalInput').modal({ 
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });

    // modal validate
    $('#userModal').validate({
        rules: {
            'pic_name': {
                required: true,
                minlength: 3
            },                 
            'pic_email': {
                required: true,
                minlength: 3,
                email: true
            },               
            'pic_phone': {
                required: true
            },
            'password': {
                required: true,                    
                minlength: 8
            },
            'password_confirm': {
                required: true,
                equalTo : "#password",                  
                minlength: 8
            },
        },
        messages: { 
            'pic_id': {
                required: 'Person in Charge (PIC) is required',
            },
            'pic_name': {
                required: 'Person in Charge (PIC) is required',
            },
            'pic_email': {
                required: 'PIC Email is required',
                minlength: 'It should contain minimum 3 characters',
                email: 'Email format is not valid'
            },
            'pic_phone': {
                required: 'PIC Phone is required',
            },                    
            'password': {
                required: 'Password is required',
                minlength: 'It should contain minimum 8 characters',
            },                    
        },
        errorClass: 'invalid-feedback animated fadeIn',
        errorElement: 'div',
        errorPlacement: (error, el) => {
            jQuery(el).addClass('is-invalid');
            jQuery(el).parents('.form-group').append(error);
        },
        highlight: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid').addClass('is-invalid');
        },
        success: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            jQuery(el).remove();
        },
    });

    // submit modal
    $('#userModal').on('submit', function (e) {
        e.preventDefault();

        if ($("#userModal").valid()) { 
            $('#submitModal').prop("disabled", true);
            $('#submitModal').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

            let route = routeStoreUserModal;

            let userId = $("#userId").val();
            let companyId = $("#companyId").val();
            let pic_name = $("#pic_name").val();
            let pic_email = $("#pic_email").val();
            let pic_phone = $("#pic_phone").val();
            let password = $("#password").val();

            let dataPost = {
                company_id: companyId,
                name: pic_name,
                email: pic_email,
                phone_number: pic_phone,
                password: password,
                user_id: userId,
            };

            storeuserAction(dataPost, route);
        }
      });
});

function listUser(idUser) {    
    const routeListUser = $("#routeListUser").val();

    let picSelected = $("#picSelected").val();    
    let companyId = $("#companyId").val();    
    let dataPost = { company_id: companyId };

    $.ajax({
        url: routeListUser,
        type: "POST",
        data: dataPost,
        success: function(response) {
            if (response.status) { 
                $("#pic_id").empty();
                $("#pic_id").append('<option selected="selected" value="">Select PIC...</option>');

                $.each(response.data,function(key, value) {
                    let selected = (idUser == value.id) ? 'selected' : '';
                    $("#pic_id").append('<option value=' + value.id + ' ' + selected + '>' + value.text + '</option>');
                });
            }
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function storeuserAction(dataPost, route) {
    $.ajax({
        url: route,
        type: "POST",
        data: dataPost,
        success: function(response) {
            alertNotify("Success, Company added PIC");

            $('#submitModal').prop("disabled", false);
            $('#submitModal').html('Save');

            $('#userModalInput').modal('hide');
            listUser(response.data.id);
        },
        error: function (xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");

            $('#submitModal').prop("disabled", false);
            $('#submitModal').html('Save');

            Swal.fire({
                html: '<strong>Oops!</strong> ' + err.message
            });
        }
    });
}

function softAction(id, name, route_name, userId, msg, msg2, msg3, status_id, redirect) {
    Swal.fire({
        title: 'Please confirm',
        text: msg2 + ' of Company ' + name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4d56',
        cancelButtonColor: '#50b380',
        confirmButtonText: 'Yes, ' + msg +' it!',
        html: false,
        preConfirm: (e) => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 50);
            });
        }
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: route_name,
                type: "POST",
                data: {
                    "company_id": id,
                    "user_id": userId,
                    "status_id": status_id
                },
                success: function(repsonse) {
                    alertNotify("Success, Company"+ " " + name +" has been " + msg3)
                    setTimeout(function(){ window.location = redirect; }, 2000);
                },
                error: function() {
                    alertNotify("Something went wrong!")
                }
            });
        }
    })
}

function alertNotify(Msg) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: Msg,
    })

    return true;
}