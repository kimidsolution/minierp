$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const companyId = $("#companyId").val()
    const routeFetch = $("#routeFetch").val()
    const userId = $("#userId").val()
    const routeFetchStore = $("#routeFetchStore").val()
    const msgRequired = "This field is required."
    const msgNumeric = "This field must numeric."
    const msgMinimumDigit = "This field minimum 4 digits"
    const numericVaid = /^[0-9]+$/

    setTimeout(() => {
        $(".part-loader").hide()
        fetchData(companyId, routeFetch)
    }, 1000)

    $(window).on('hidden.bs.modal', function () {
        removeError(".account_code", ".invalidCode")
        removeError(".account_name", ".invalidName")
    })

    $(document).on('keyup', '.account_name', function (e) {
        e.preventDefault()
        if ($(this).val() === "") {
            displayError(".account_name", ".invalidName", msgRequired)
        } else {
            removeError(".account_name", ".invalidName")
        }
    })

    $(document).on('keyup', '.account_code', function (e) {
        e.preventDefault()
        if ($(this).val() === "") {
            displayError(".account_code", ".invalidCode", msgRequired)
        } else if (!$(this).val().match(numericVaid)) {
            displayError(".account_code", ".invalidCode", msgNumeric)
        } else if ($(this).val().length < 4) {
            displayError(".account_code", ".invalidCode", msgMinimumDigit)
        } else {
            removeError(".account_code", ".invalidCode")
        }
    })

    $(document).on('click', '.btn-submit', function(e) {
        let dataId = $(this).data("id")
        actionForm(e, dataId)
    })

    function fetchData(idCompany, route) {
        try {
            $.ajax({
                url: route,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': token },
                datatype: "html",
                beforeSend: () => {
                    $(".part-loader").show()
                    $(".div-tree-account").empty()
                },
                data: { 'company_id': idCompany },
                success: function (response) {
                    $(".part-loader").hide()
                    if (response.data.length > 0) {
                        let html = `
                        <div style="position: absolute; top: 0; right: 0;">
                            <div class="toast fade" data-autohide="false" role="alert" aria-live="assertive" aria-atomic="true" data-toggle="toast">
                                <div class="toast-header">
                                    <strong class="mr-auto">Notification</strong>
                                    <small class="text-muted current-time"></small>
                                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body fill-message">
                                </div>
                            </div> <!--end toast-->
                        </div>
                        <ul><li>
                        <span class="account-tree-title">
                            <a style="color:#425176; text-decoration:none;"
                                data-toggle="collapse"
                                href="#accountType"
                                aria-expanded="true"
                            >
                                <i class="collapsed">
                                    <span class="arrow-nested">►</span><i class="fas fa-folder text-warning"></i>
                                </i>
                                <i class="expanded">
                                    <span class="arrow-nested">▼</span><i class="far fa-folder-open text-warning"></i>
                                </i>
                                Account Type
                            </a>
                        </span>
                        <div id="accountType" class="collapse show"><ul>`
                        response.data.forEach((accountType, index) => {
                            let hasArrrow = (accountType.accounts.length > 0) ? '►' : '▼'
                            html += `<div class="modal fade modal-create-action-accounts-`+ index + `-` + accountType.account_type_id +`" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0">Create Account</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body profile-card">
                                                    <div class="media align-items-center">
                                                        <div class="media-body align-self-center">
                                                            <h5 class="pro-title">`+ accountType.account_type_name  +`</h5>
                                                            <p class="mb-1 text-muted">Level 1</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form class="jq-validation-form jq-validation-form-`+ `create-` + index + `-` + accountType.account_type_id +`" data-id="modal-create-action-accounts-`+ index + `-` + accountType.account_type_id +`">
                                                <input type="hidden" name="account_level" value="`+ 1 +`">
                                                <input type="hidden" name="account_type_id" value="`+ accountType.account_type_id +`">
                                                <input type="hidden" name="account_parent_id">
                                                <input type="hidden" name="account_parent_name">
                                                <input type="hidden" name="index_form" value="`+ index +`">
                                                <input type="hidden" name="account_type_name" value="`+ accountType.account_type_name +`">
                                                <input type="hidden" name="item_class" value="item-`+ index + `-` + accountType.account_type_id +`">
                                                <input type="hidden" name="title_item_class" value="title-item-`+ index + `-` + accountType.account_type_id +`">
                                                <div class="form-row">
                                                    <div class="col-md-6 form-group">
                                                        <label for="name">Account Code <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                            maxlength="8"
                                                            name="account_code"
                                                            class="form-control account_code"
                                                            id="create-code-`+ index + `-` + accountType.account_type_id +`"
                                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                        >
                                                        <div id="account_code-error" class="invalid-feedback animated fadeIn invalidCode"></div>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label for="name">Account Name <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control account_name"
                                                            name="account_name"
                                                            placeholder="please fill account name"
                                                            id="create-name-`+ index + `-` + accountType.account_type_id +`"
                                                        >
                                                        <div id="account_name-error" class="invalid-feedback animated fadeIn invalidName">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-12 form-group">
                                                        <label for="desciption">Description</label>
                                                        <textarea class="form-control account_description" id="create-desc-`+ index + `-` + accountType.account_type_id +`" name="account_description" rows="2">
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 5%; float: right;">
                                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                                    <button type="button" data-id="`+ 'create-' + index + `-` + accountType.account_type_id +`" class="btn btn-sm btn-primary btn-submit">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <li class="item-`+ index + `-` + accountType.account_type_id +`">
                                <span class="account-tree-title">
                                    <a style="color:#425176; text-decoration:none;"
                                        data-toggle="collapse"
                                        href="#page`+ index + `-` + accountType.account_type_id +`"
                                        aria-expanded="false"
                                        aria-controls="page`+ index + `-` + accountType.account_type_id +`"
                                    >
                                        <i class="collapsed">
                                            <span class="arrow-nested">`+ hasArrrow + `</span><i class="fas fa-folder text-warning"></i>
                                        </i>
                                        <i class="expanded">
                                            <span class="arrow-nested">▼</span><i class="far fa-folder-open text-warning"></i>
                                        </i>
                                        <span class="title-item-`+ index + `-` + accountType.account_type_id +`">
                                            `+ accountType.account_type_name +`
                                        </span>
                                    </a>
                                </span>
                                <div class="action-tree-account">
                                    <div class="dropdown d-inline-block float-right mt-n2">
                                        <a class="nav-link dropdown-toggle arrow-none" id="drop1"
                                            data-toggle="dropdown"
                                            href="#"
                                            role="button"
                                            aria-haspopup="false"
                                            aria-expanded="false"
                                        >
                                            <i class="fas fa-ellipsis-v font-18 text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop1">
                                            <a style="cursor: pointer;" class="dropdown-item" data-toggle="modal" data-animation="bounce" data-target=".modal-create-action-accounts-`+ index + `-` + accountType.account_type_id +`">
                                                <i class="fas fa-file-medical text-info"></i>
                                                <span style="margin-left: 20px;">Add Account</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div id="page`+ index + `-` + accountType.account_type_id +`" class="collapse">
                                    `+ createList(accountType.accounts, accountType.account_type_name) +`
                                </div>
                            </li>`
                        })
                        html += `</ul></div></li></ul>`
                        $(".div-tree-account").html(html)
                    }
                }
            })
        } catch (error) {
            console.log(error)
        }
    }

    function createList(accounts, accountTypeName) {
        let html = `<ul>`
        accounts.forEach((account, index) => {
            let hasArrrow = (account.childrens.length > 0) ? '►' : '▼'
            let accountDesc = account.description === null ? '' : account.description
            html += `<li class="item-`+ index + `-` + account.id +`">
            <span class="account-tree-title">
                <a style="color:#425176; text-decoration:none;"
                    data-toggle="collapse"
                    href="#page`+  index + `-` + account.id + `"
                    aria-expanded="false"
                    aria-controls="page`+  index + `-` + account.id + `"
                >
                    <i class="collapsed">
                        <span class="arrow-nested">`+ hasArrrow + `</span><i class="fas fa-folder text-warning"></i>
                    </i>
                    <i class="expanded">
                        <span class="arrow-nested">▼</span><i class="far fa-folder-open text-warning"></i>
                    </i>
                    <span class="title-item-`+ index + `-` + account.id +`">
                        `+ account.name +`
                    </span>
                </a>
            </span>
            <div class="action-tree-account">
                <div class="dropdown d-inline-block float-right mt-n2">
                    <a class="nav-link dropdown-toggle arrow-none" id="drop1"
                        data-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-haspopup="false"
                        aria-expanded="false"
                    >
                        <i class="fas fa-ellipsis-v font-18 text-muted"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop1">
                        <a style="cursor: pointer;" class="dropdown-item" data-toggle="modal" data-animation="bounce" data-target=".modal-create-action-accounts-`+  index + `-` + account.id + `">
                            <i class="fas fa-file-medical text-info"></i>
                            <span style="margin-left: 20px;">Add Account</span>
                        </a>
                        <a style="cursor: pointer;" class="dropdown-item" data-toggle="modal" data-animation="bounce" data-target=".modal-edit-action-accounts-`+  index + `-` + account.id + `">
                            <i class="fas fa-edit text-info"></i>
                            <span style="margin-left: 17px;">Edit Account</span>
                        </a>
                    </div>
                </div>
            </div>`
            if (account.childrens.length > 0) {
                html += `<ul>
                    <div id="page` +  index + `-` + account.id +  `" class="collapse">`
                        + createList(account.childrens, accountTypeName) +
                    `</div>
                </ul>`
            }
            html += `</li>
            <div class="modal fade modal-create-action-accounts-`+ index + `-` + account.id +`" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Create Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body profile-card">
                                    <div class="media align-items-center">
                                        <div class="media-body align-self-center">
                                            <h5 class="pro-title">`+ accountTypeName  +`</h5>
                                            <p class="mb-1 text-muted">Level `+ (account.level + 1) +`</p>
                                            <p class="mb-1 text-muted">Parent account: `+ account.name +`</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form class="jq-validation-form jq-validation-form-`+ `create-`+ index + `-` + account.id +`" data-id="modal-create-action-accounts-`+ index + `-` + account.id +`">
                                <input type="hidden" name="account_level" value="`+ (account.level + 1) +`">
                                <input type="hidden" name="account_type_id" value="`+ account.account_type_id +`">
                                <input type="hidden" name="account_parent_id" value="`+ account.id +`">
                                <input type="hidden" name="account_parent_name" value="`+ account.name +`">
                                <input type="hidden" name="index_form" value="`+ index+`">
                                <input type="hidden" name="account_type_name" value="`+ accountTypeName +`">
                                <input type="hidden" name="item_class" value="item-`+ index + `-` + account.id +`">
                                <input type="hidden" name="title_item_class" value="title-item-`+ index + `-` + account.id +`">
                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label for="name">Account Code <span class="text-danger">*</span></label>
                                        <input type="text"
                                            maxlength="8"
                                            name="account_code"
                                            class="form-control account_code"
                                            id="create-code-`+ index + `-` + account.id +`"
                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                        >
                                        <div id="account_code-error" class="invalid-feedback animated fadeIn invalidCode">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="name">Account Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control account_name"
                                            name="account_name"
                                            placeholder="please fill account name"
                                            id="create-name-`+ index + `-` + account.id +`"
                                        >
                                        <div id="account_name-error" class="invalid-feedback animated fadeIn invalidName">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <label for="desciption">Description</label>
                                        <textarea class="form-control account_description" id="create-desc-`+ index + `-` + account.id +`" name="account_description" rows="2">
                                        </textarea>
                                    </div>
                                </div>
                                <div style="margin-top: 5%; float: right;">
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                    <button type="button" data-id="`+ 'create-' + index + `-` + account.id +`" class="btn btn-sm btn-primary btn-submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade modal-edit-action-accounts-`+ index + `-` + account.id +`" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Edit Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body profile-card">
                                    <div class="media align-items-center">
                                        <div class="media-body align-self-center">
                                            <h5 class="pro-title">`+ accountTypeName  +`</h5>
                                            <p class="mb-1 text-muted">Level `+ account.level +`</p>
                                            <p class="mb-1 text-muted">Parent account: `+ account.name +`</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form class="jq-validation-form jq-validation-form-`+ `edit-`+ index + `-` + account.id +`" data-id="modal-edit-action-accounts-`+ index + `-` + account.id +`">
                                <input type="hidden" name="account_id" value="`+ account.id +`">
                                <input type="hidden" name="account_level" value="`+ account.level +`">
                                <input type="hidden" name="account_type_id" value="`+ account.account_type_id +`">
                                <input type="hidden" name="account_parent_id" value="`+ account.parent_account_id +`">
                                <input type="hidden" name="account_parent_name" value="`+ account.name +`">
                                <input type="hidden" name="index_form" value="`+ index +`">
                                <input type="hidden" name="account_type_name" value="`+ accountTypeName +`">
                                <input type="hidden" name="item_class" value="item-`+ index + `-` + account.id +`">
                                <input type="hidden" name="title_item_class" value="title-item-`+ index + `-` + account.id +`">
                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label for="name">Account Code <span class="text-danger">*</span></label>
                                        <input type="text"
                                            maxlength="8"
                                            name="account_code"
                                            class="form-control account_code"
                                            value="`+ account.number +`"
                                            id="edit-code-`+ index + `-` + account.id +`"
                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                        >
                                        <div id="account_code-error" class="invalid-feedback animated fadeIn invalidCode">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="name">Account Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control account_name"
                                            name="account_name"
                                            placeholder="please fill account name"
                                            value="`+ account.name +`"
                                            id="edit-name-`+ index + `-` + account.id +`"
                                        >
                                        <div id="account_name-error" class="invalid-feedback animated fadeIn invalidName">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <label for="desciption">Description</label>
                                        <textarea class="form-control account_description" id="edit-desc-`+ index + `-` + account.id +`" name="account_description" rows="2">`+ accountDesc +`</textarea>
                                    </div>
                                </div>
                                <div style="margin-top: 5%; float: right;">
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                    <button type="button" data-id="`+ 'edit-' + index + `-` + account.id +`" class="btn btn-sm btn-primary btn-submit">Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>`
        })
        html += '</ul>'
        return html
    }

    function actionForm(e, id) {
        e.preventDefault()
        let prefixFormId = ".jq-validation-form-" + id
        let formId = $(prefixFormId).data("id")
        let serializeObject = $(prefixFormId).serializeObject()
        let validate = isInValid(serializeObject.account_code, serializeObject.account_name)

        if (serializeObject.account_code === "") displayError(".account_code", ".invalidCode", msgRequired)
        if (serializeObject.account_name === "") displayError(".account_name", ".invalidName", msgRequired)
        if (serializeObject.account_parent_id === "null") serializeObject.account_parent_id = null
        if (!validate) {
            storeData(e, serializeObject, routeFetchStore, "." + formId, serializeObject.account_id === undefined)
        }
        return false
    }

    function storeData(e, request, url_route, formDataId, isCreate) {
        let html = ``
        e.preventDefault()
        request.user_id = userId
        $.ajax({
            url: url_route,
            type: "POST",
            headers: { "Authorization": `Bearer ${token}` },
            data: request,
            beforeSend: () => {
                html = ``
                $(".part-loader").show()
            },
            success: function (response) {
                $(".part-loader").hide()
                if (response.status === true) {
                    $(formDataId).modal('hide')
                    let titleMsg = isCreate ? 'created' : 'updated'
                    let timingEvent = timeSince(new Date(Date.now()))
                    let itemClass = "." + request.item_class
                    let prefixId = isCreate ? 'create-' : 'edit-'
                    let buttonId = prefixId + request.index_form + '-' + response.data.id
                    notifAlert("Success, Account has been " + titleMsg, timingEvent)
                    if (isCreate) {
                        html += listHtml(response, request)
                        $(itemClass).append(html).show('slow')
                        actionForm(e, buttonId)
                    } else {
                        let titleItemClass = "." + request.title_item_class
                        let idCode = "#" + "edit-code-" + request.index_form + "-" + response.data.id
                        let idName = "#" + "edit-name-" + request.index_form + "-" + response.data.id
                        let idDesc = "#" + "edit-desc-" + request.index_form + "-" + response.data.id
                        $(idCode).val(response.data.number)
                        $(idName).val(response.data.name)
                        $(idDesc).val(response.data.description)
                        $(titleItemClass).text(response.data.name)
                    }
                } else {
                    if (response.status === false) {
                        displayError(".account_code", ".invalidCode", response.message)
                    }
                }
            },
            error: function() {
                $(".part-loader").hide()
                alertNotify("Something went wrong!")
            }
        });
    }

    function listHtml(response, request) {
        let account = response.data
        let index = request.index_form
        let parent_name = request.account_parent_name
        let type_name = request.account_type_name
        let accountDesc = account.description === null ? '' : account.description
        return `<ul><li class="item-`+  index + `-` + account.id + `">
                <span class="account-tree-title">
                    <a style="color:#425176; text-decoration:none;"
                        data-toggle="collapse"
                        href="#page`+  index + `-` + account.id + `"
                        aria-expanded="false"
                        aria-controls="page`+  index + `-` + account.id + `"
                    >
                        <i class="collapsed">
                            <span class="arrow-nested">►</span>
                            <i class="fas fa-folder text-warning"></i>
                        </i>
                        <i class="expanded">
                            <span class="arrow-nested">▼</span>
                            <i class="far fa-folder-open text-warning"></i>
                        </i>
                        <span class="title-item-`+ index + `-` + account.id +`">
                            `+ account.name +`
                        </span>
                    </a>
                </span>
                <div class="action-tree-account">
                    <div class="dropdown d-inline-block float-right mt-n2">
                        <a class="nav-link dropdown-toggle arrow-none" id="drop1"
                            data-toggle="dropdown"
                            href="#"
                            role="button"
                            aria-haspopup="false"
                            aria-expanded="false"
                        >
                            <i class="fas fa-ellipsis-v font-18 text-muted"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop1">
                            <a style="cursor: pointer;" class="dropdown-item" data-toggle="modal" data-animation="bounce" data-target=".modal-create-action-accounts-`+  index + `-` + account.id + `">
                                <i class="fas fa-file-medical text-info"></i>
                                <span style="margin-left: 20px;">Add Account</span>
                            </a>
                            <a style="cursor: pointer;" class="dropdown-item" data-toggle="modal" data-animation="bounce" data-target=".modal-edit-action-accounts-`+  index + `-` + account.id + `">
                                <i class="fas fa-edit text-info"></i>
                                <span style="margin-left: 17px;">Edit Account</span>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            </ul>
            <div class="modal fade modal-create-action-accounts-`+ index + `-` + account.id +`" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">Create Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body profile-card">
                                    <div class="media align-items-center">
                                        <div class="media-body align-self-center">
                                            <h5 class="pro-title">`+ type_name  +`</h5>
                                            <p class="mb-1 text-muted">Level `+ (account.level + 1) +`</p>
                                            <p class="mb-1 text-muted">Parent account: `+ parent_name +`</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form class="jq-validation-form jq-validation-form-create-`+ index + `-` + account.id +`" data-id="modal-create-action-accounts-`+ index + `-` + account.id +`">
                                <input type="hidden" name="account_level" value="`+ (account.level + 1 ) +`">
                                <input type="hidden" name="account_type_id" value="`+ account.account_type_id +`">
                                <input type="hidden" name="account_parent_id" value="`+ account.id +`">
                                <input type="hidden" name="account_parent_name" value="`+ account.name +`">
                                <input type="hidden" name="index_form" value="`+ index +`">
                                <input type="hidden" name="account_type_name" value="`+ type_name +`">
                                <input type="hidden" name="item_class" value="item-`+ index + `-` + account.id +`">
                                <input type="hidden" name="title_item_class" value="title-item-`+ index + `-` + account.id +`">
                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label for="name">Account Code <span class="text-danger">*</span></label>
                                        <input type="text"
                                            maxlength="8"
                                            name="account_code"
                                            class="form-control account_code"
                                            id="create-code-`+ index + `-` + account.id +`"
                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                        >
                                        <div id="account_code-error" class="invalid-feedback animated fadeIn invalidCode">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="name">Account Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control account_name"
                                            name="account_name"
                                            placeholder="please fill account name"
                                            id="create-name-`+ index + `-` + account.id +`"
                                        >
                                        <div id="account_name-error" class="invalid-feedback animated fadeIn invalidName">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <label for="desciption">Description</label>
                                        <textarea class="form-control account_description" id="create-desc-`+ index + `-` + account.id +`" name="account_description" rows="2">
                                        </textarea>
                                    </div>
                                </div>
                                <div style="margin-top: 5%; float: right;">
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                    <button type="button" data-id="`+ 'create-' + index + `-` + account.id +`" class="btn btn-sm btn-primary btn-submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div  class="modal fade modal-edit-action-accounts-`+ index + `-` + account.id +`" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0">Edit Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body profile-card">
                                <div class="media align-items-center">
                                    <div class="media-body align-self-center">
                                        <h5 class="pro-title">`+ type_name +`</h5>
                                        <p class="mb-1 text-muted">Level `+ account.level +`</p>
                                        <p class="mb-1 text-muted">Parent account: `+ parent_name +`</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="jq-validation-form jq-validation-form-edit-`+ index + `-` + account.id +`" data-id="modal-edit-action-accounts-`+ index + `-` + account.id +`">
                            <input type="hidden" name="account_id" value="`+ account.id +`">
                            <input type="hidden" name="account_level" value="`+ account.level +`">
                            <input type="hidden" name="account_type_id" value="`+ account.account_type_id +`">
                            <input type="hidden" name="account_parent_id" value="`+ account.parent_account_id +`">
                            <input type="hidden" name="account_parent_name" value="`+ parent_name +`">
                            <input type="hidden" name="index_form" value="`+ index +`">
                            <input type="hidden" name="account_type_name" value="`+ type_name +`">
                            <input type="hidden" name="item_class" value="item-`+ index + `-` + account.id +`">
                            <input type="hidden" name="title_item_class" value="title-item-`+ index + `-` + account.id +`">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="name">Account Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                        maxlength="8"
                                        name="account_code"
                                        class="form-control account_code"
                                        value="`+ account.number +`"
                                        id="edit-code-`+ index + `-` + account.id +`"
                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                    >
                                    <div id="account_code-error" class="invalid-feedback animated fadeIn invalidCode">
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="name">Account Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control account_name"
                                        name="account_name"
                                        placeholder="please fill account name"
                                        value="`+ account.name +`"
                                        id="edit-name-`+ index + `-` + account.id +`"
                                    >
                                    <div id="account_name-error" class="invalid-feedback animated fadeIn invalidName">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label for="desciption">Description</label>
                                    <textarea class="form-control account_description" id="edit-desc-`+ index + `-` + account.id +`" name="account_description" rows="2">`+ accountDesc +`</textarea>
                                </div>
                            </div>
                            <div style="margin-top: 5%; float: right;">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                                <button type="button" data-id="`+ 'edit-' + index + `-` + account.id +`" class="btn btn-sm btn-primary btn-submit">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>`
    }

    function notifAlert(message, time) {
        setTimeout(() => {
            $('.toast').toast('show')
            $('.fill-message').text(message)
            $('.current-time').text(time + ' ago')
        }, 10)
        setTimeout(() => {
            setTimeout(() => {
                $('.toast').toast('hide')
            }, 300)
        }, 2500)
    }

    function isInValid(objectCode, objectName) {
        return objectCode === "" || objectName === "" || !objectCode.match(numericVaid) || objectCode.length < 4
    }

    function displayError(inputSelector, divSelector, msg) {
        $(inputSelector).addClass("is-invalid")
        $(divSelector).css("display", "block")
        $(divSelector).text(msg)
    }

    function removeError(inputSelector, divSelector) {
        $(inputSelector).removeClass("is-invalid")
        $(divSelector).css("display", "none")
        $(divSelector).text("")
    }

    function alertNotify(Msg) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: Msg,
        })

        return false;
    }
})
