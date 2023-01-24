$(document).ready(function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#mark').change(function (){
        let mark_id=$(this).val();
        $('#model').html('');
        $.ajax({
            type:'get',
            url:'/getModel',
            data:{'id':mark_id},
            success:function (result){
                $('#model').html('<option value="" selected disabled>Выберите</option>');
                $.each(result, function (key, value){
                    $('#model').append('<option   value="'+value.id+'"  >'+value.name+'</option>')
                });

                let oldModel = $("#model").attr("data-selected-model");
                if(oldModel !== '')
                {
                    $("#model").val(oldModel);
                }
            },
            error:function (){

            }
        });
    });
});
$(document).ready(function() {
    let OldMark = $("#mark").attr("data-selected-mark");
    if(OldMark !== '') {
        $('#mark').val(OldMark );


        $("#mark").change();
    }
});
