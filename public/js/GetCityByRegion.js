$(document).ready(function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#region').change(function (){
        let region_id=$(this).val();
        $('#city').html('');
        $.ajax({
            type:'get',
            url:'/getCity',
            data:{'id':region_id},
            success:function (result){
                $('#city').html('<option value="" selected disabled>Выберите</option>');
                $.each(result, function (key, value){
                    $('#city').append('<option   value="'+value.id+'"  >'+value.name+'</option>')
                });

            let oldCity = $("#city").attr("data-selected-city");
            if(oldCity !== '')
                {
                    $("#city").val(oldCity);
                }
            },
            error:function (){

            }
        });
    });
});
$(document).ready(function() {
    let OldRegion = $("#region").attr("data-selected-region");
    if(OldRegion !== '') {
        $('#region').val(OldRegion );


        $("#region").change();
    }
});
