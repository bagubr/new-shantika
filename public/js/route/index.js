// var app = new Vue({
//     el: '#app',
//     data: {
//         message: 'Hello Vue!',
//         test: [],
//         area_id: 1,
//         agency: [{
//             id: '',
//         }]
//     },
//     mounted() {
//         this.getData();
//     },
//     methods: {
//         getData() {
//             axios
//                 .get('/agency/all', {
//                     params: {
//                         area_id: this.area_id
//                     },
//                 })
//                 .then(result => {
//                     this.test = result.data.agencies;
//                     this.agency = [
//                         {
//                             id: ''
//                         }
//                     ];
//                 });
//         },
//         addField() {
//             this.agency.push({ id: '' })
//         },
//         removeField(index) {
//             this.agency.splice(index, 1);
//         },
//     },
// })
$(document).ready(function () {
    $("#addRow").addClass("d-none");
    $("#area_id").change(function () {
        var selectedCountry = $(this).children("option:selected").val();
        $.ajax({
            url: "/agency/all",
            type: "get",
            data: {
                area_id: selectedCountry,
            },
            success: function (response) {
                $("#addRow").removeClass("d-none");
                $("#refresh").removeClass("d-none");
                $("#area_id").attr("disabled", true);
                var options;
                $.each(response.agencies, function (index, value) {
                    options = options + '<option value="' + value.id + '">' + '(' + value.city_name + ') ' + value.name + '</option>';
                });
                var skillhtml = '<select class="form-control select2" name="agency_id[]" required>' + options + '</select>';
                $("#container").html(skillhtml);


                $("#addRow").click(function (value) {
                    var html = '';
                    html += '<div id="inputFormRow">';
                    html += '<div class="input-group mb-3">';
                    html += '<select name="agency_id[]" class="form-control myselect" required>';
                    html += options + '<option value="' + value.id + '">' + '(' + value.city_name + ') ' + value.name + '</option>';
                    html += '<select>';
                    html += '<div class="input-group-append">';
                    html += '<button id="removeRow" type="button" class="btn btn-danger">Hapus</button>';
                    html += '</div>';
                    html += '</div>';
                    $('#container2').append(html);
                    if ($('.myselect').length > 0) {
                        $('.myselect').select2();
                    };
                });
                $(document).on('click', '#removeRow', function () {
                    $(this).closest('#inputFormRow').remove();
                });
                if ($('.select2').length > 0) {
                    $('.select2').select2();
                };
            },
            error: function (xhr) {
                console.log(xhr)
            }
        });
    });


});