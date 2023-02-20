$(document).ready(function() {
    setTimeout(() => {
        get_registration_data();
    }, 1000);
    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-zA-Z\s_]+$/i.test(value);
    }, "Letters only please");

    $("body").on("keyup", ".removedspace", function(e) {
        var val = $(this).val();
        $(this).val(val.trim());
    });

    $(document).on('keyup', '.phone', function() {
        var yourInput = $(this).val();
        re = /[`~!@#$%^&*_|+\=?;:'",ABCDEFGHIJKLMNOPQRSTUVWXyzabcdefghijklmnopqrstuvwxyz.<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if (isSplChar) {
            var no_spl_char = yourInput.replace(/[`~!@#$%^&*_|+\=?;:'",ABCDEFGHIJKLMNOPQRSTUVWXyzabcdefghijklmnopqrstuvwxyz.<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl_char);
        }
    });

    $('.checkspecialchar').keyup(function() {
        var yourInput = $(this).val();
        re = /[`~@#$%^*()+\=;:'"<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if (isSplChar) {
            var no_spl_char = yourInput.replace(/[`~@#$%^*()+\=;:'"<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl_char);
        }
    });


    $("body").find('.datepicker').datepicker({
        dateFormat: "mm/dd/yy",
        dayNamesMin: ["S", "M", "T", "W", "T", "F", "S"],
        changeMonth: true,
        changeYear: true,
        maxDate: 0,
        yearRange: '1998:2150',
        onSelect: function(dateText, inst) {
            $('.datepicker').parent().find('i').hide();
        }
    });

    var _URL = window.URL || window.webkitURL;
    $('#photo').on('change', function() {
        var file, img;
        var ext = this.value.match(/\.(.+)$/)[1];
        switch (ext) {
            case "jpg":
            case "jpeg":
            case "png":
            case "JPG":
            case "JPEG":
            case "PNG":
                break;
            default:
                this.value = "";
                alert('Invalid Image Format.Please Select jpg or jpeg or png image file type');
                return false;
        }
        file = this.files[0];
        if (file.size > 1000000) {
            alert('Max Upload size is 1 MB only');
            this.value = "";
            return false;
        }

        if ((file)) {
            img = new Image();
            var objectUrl = _URL.createObjectURL(file);
            img.onload = function() {
                if (this.width < 100 || this.height < 100) {
                    alert('Minimum image dimensions 100px * 100px');
                    $('#photo').val('');
                }
            };
            img.src = objectUrl;
        }
    });

    $('#children_frm').validate({
        errorElement: "i",
        errorClass: "msg-error",
        rules: {
            name: {
                required: true,
                lettersonly: true,
                minlength: 3,
                maxlength: 70
            },
            date_of_birth: {
                required: true
            },
            class: {
                required: true
            },
            country_id: {
                required: true
            },
            state_id: {
                required: true
            },
            city_id: {
                required: true
            },
            zipcode: {
                required: true
            },
            address: {
                required: true,
                minlength: 3,
                maxlength: 300
            },
            photo: {
                required: true
            }
        },
        submitHandler: function(form) {
            if (checkInput()) {
                $("#frm_btn").prop("disabled", true).html('Please Wait..');
                var data = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: SITE_URL + "handle-registration",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#frm_btn").prop("disabled", false).html('Submit');
                        if (response.success) {
                            template_counter = 0;
                            $('.message').html(response.message).show().addClass('success');
                            $("#children_frm")[0].reset();
                            $('#append_relation_template').html('');
                            get_registration_data();
                        } else {
                            $('.message').html(response.message).show().addClass('error');
                        }
                        setTimeout(function() {
                            $('.message').removeClass('error').removeClass('success').hide();
                        }, 3000);
                    }
                });
            }
        }
    });

    $(document).on('change', '#country_id, #state_id', function() {
        let country_id = $('#country_id').val();
        let state_id = $('#state_id').val();
        let selected_attr = $(this).attr('id');
        let url = SITE_URL + 'get-option-value';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                'country_id': country_id,
                'state_id': state_id,
                'selected_attr': selected_attr,
            },
            dataType: 'json',
            beforeSend: function() {
                if (selected_attr == "country_id") {
                    $('#city_id').html('<option value="">Select City</option>');
                }
            },
            success: function(resp) {
                if (resp.status) {
                    if (selected_attr == "country_id") {
                        let stateHtml = '<option value="">Select State</option>';
                        $.each(resp.data, function(key, value) {
                            stateHtml += `<option value="${value.id}">${value.name}</option>`;
                        });
                        $('#state_id').html(stateHtml);
                    } else if (selected_attr == "state_id") {
                        let cityHtml = '<option value="">Select City</option>';
                        $.each(resp.data, function(key, value) {
                            cityHtml += `<option value="${value.id}">${value.name}</option>`;
                        });
                        $('#city_id').html(cityHtml);
                    }
                }
            }
        });
    });

    let relation_array_data = ['Father', 'Mother', 'Brother', 'Sister', 'Grandfather', 'Grandmother'];

    let template_counter = 0;
    $(document).on('click', '#add_more', function() {
        if (template_counter < 5) {
            template_counter++;
            let generate_relation_option = [];
            if (relation_array_data.length > 0) {
                $.each($('.select-relation option:selected'), function() {
                    for (var i = 0; i < relation_array_data.length; i++) {
                        if (relation_array_data[i] === $(this).val()) {
                            relation_array_data.splice(i, 1);
                        }
                    }
                });

                $.each(relation_array_data, function(key, val) {
                    generate_relation_option.push('<option value="' + val + '">' + val + '</option>');
                });
            }
            generate_pickup_relation_template(template_counter, generate_relation_option);
        } else {
            alert('You can only create 6 pickup relationship');
        }
    });

    $(document).on('change', '.select-relation', function() {
        let changeDivId = $(this).attr('rel');
        for (var i = 0; i < changeDivId; i++) {
            let name = $(this).val();
            for (var j = 0; j <= i; j++) {
                $("#select_relationship_" + i).children('option[value=' + name + ']').prop('disabled', true);
            }
        }
    });

    $(document).on('click', '.btn_remove', function() {
        let remove_section_id = $(this).attr('rel');
        let relationship_name = $('#select_relationship_' + remove_section_id).val();
        if (relation_array_data.indexOf(relationship_name) === -1) {
            relation_array_data.push(relationship_name);
        }
        $('#relation_section_div_' + remove_section_id).remove();
        template_counter--;
    });

    function generate_pickup_relation_template(counter, relation_name) {
        let templateHtml = `<br>
            <div class="row relation_section" id="relation_section_div_${counter}">
                <div class="col-md-4">
                    <label class="form-label">Person Names</label>
                    <input type="text" class="form-control checkspecialchar valid" name="person_name[]" maxlength="70"/>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Picked-Up detail</label>
                    <select class="select form-control select-relation valid" name="relationship[]" id="select_relationship_${counter}" rel="${counter}">
                        ${relation_name}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control removedspace phone validPhone" name="phone[]" maxlength="10">
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0);" class="button btn-danger btn_remove" rel="${counter}">X</a>
                </div>
            </div>`;

        $('#append_relation_template').append(templateHtml);
    }
});

function get_registration_data() {
    $.ajax({
        type: 'POST',
        url: SITE_URL + 'get-registration-data',
        data: {},
        success: function(response) {
            var html = '';
            if (response.status) {
                $.each(response.data, function(key, value) {
                    var title = '';
                    title += `<table class="table">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Phone</th>
                    </tr>
                </thead>
                <tbody>`;

                    $.each(value.details, function(k, v) {
                        title += `<tr>
                    <th>${k+1}</th>
                    <td>${v.name}</td>
                    <td>${v.relationship}</td>
                    <td>${v.phone}</td>
                    </tr>`;
                    });

                    title += `</tbody></table>`;

                    html += `<tr>
                    <th scope="row">${key+1}</th>
                    <td>${value.name}</td>
                    <td>${value.created_at_show}</td>
                    <td>${value.class}</td>
                    <td>${value.country.name}</td>
                    <td>${value.state.name}</td>
                    <td>${value.city.name}</td>
                    <td>${value.zipcode}</td>
                    <td>${value.address}</td>
                    <td><img src="${SITE_URL+'storage/photos/'+value.photo}" style="width:100px"></td>
                    <td><span>${title}</span></td>
                </tr>`;
                });
                $('#tableData').html(html);
            } else {
                $('#tableData').html(`<tr><th style="text-align: center;" scope="row" colspan="11">No records found</th></tr>`);
            }
        },
        error: function() {},
    });
}


function checkInput() {
    var count = 1;
    $('.msg-error').remove();
    if ($("#children_frm").find('input[name="person_name[]"]').length > 0) {
        $("#children_frm").find('input[name="person_name[]"]').each(function() {
            if ($(this).val() == '') {
                $(this).after('<i id="person_name-error[]" class="msg-error">Please enter person name.</i>');
                count = count * 0;
            } else {
                $(this).parent().find(".msg-error").remove();
            }
        })
    }
    if ($("#children_frm").find('select[name="relationship[]"]').length > 0) {
        $("#children_frm").find('select[name="relationship[]"]').each(function() {
            if ($(this).val() == '') {
                $(this).after('<i id="relationship-error[]" class="msg-error">Please select relationship.</i>');
                count = count * 0;
            } else {
                $(this).parent().find(".msg-error").remove();
            }
        })
    }

    if ($("#children_frm").find('input[name="phone[]"]').length > 0) {
        $("#children_frm").find('input[name="phone[]"]').each(function() {
            if ($(this).val() == '') {
                $(this).after('<i id="phone-error[]" class="msg-error">Please enter phone.</i>');
                count = count * 0;
            } else {
                $(this).parent().find(".msg-error").remove();
            }
        })
    }
    return count == 1;
}