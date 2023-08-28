var token=$('meta[name="csrf-token"]').attr('content');
$(document).ready(function() {
    if(window.location.href.indexOf('mis-favoritas') > -1)
        getFavorites();
});
function searchMovies(){
    let title=$('#title').val();
    if(title!=''){
        $('#backdrop').removeClass('none');
        $.ajax({
            url:'search-movies/'+title,
            type:'GET',
            success: (data)=>{
                let arr=JSON.parse(data);
                $('#result-search').html('');
                for(i=0;i<arr.length;i++){
                    let image=arr[i].img;
                    if(arr[i].img=='N/A')
                        image="images/no-photo.jpg";
                    $('#result-search').append(
                        '<div class="col-lg-3" style="height:500px;margin-bottom:30px">'+
                            '<div class="card">'+
                                '<img src="'+image+'" class="card-img-top" alt="" style="width:250px">'+
                                '<div class="card-body">'+
                                    '<p><b>Título</b>: '+arr[i].title+'</p>'+
                                    '<p><b>Año</b>: '+arr[i].year+'</p>'+
                                    '<p><b>Rating</b>: '+arr[i].rating+'</p>'+
                                    '<p><button class="btn btn-warning" onclick="addFavorites(\''+arr[i].title+'\',\''+arr[i].year+'\',\''+arr[i].rating+'\',\''+arr[i].img+'\',\''+arr[i].imdbID+'\')">Agregar a favoritas</button></p>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                }
                $('#backdrop').addClass('none');
            }
        });
    }
    else
        bootbox.alert('Debes escribir el título de la película.')
}
function addFavorites(mtitle,myear,mrate,mimg,mimdbID){
    $('#backdrop').removeClass('none');
    $.ajax({
        url:'check-duplicates/'+mimdbID,
        type:'GET',
        success: (data)=>{
            if(data==0){
                $.ajax({
                    url:'favorites',
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        _token:token,title:mtitle,year:myear,rate:mrate,img:mimg,imdbID:mimdbID
                    },
                    success: (data2)=>{
                        $('#backdrop').addClass('none');
                        if(data2.id!=0 && data2.id!=null)
                            bootbox.alert('Se agregó '+mtitle+' a tus películas favoritas.')
                        else
                            bootbox.alert('Ocurrió un error al guardar la película.')
                    }
                });
            }
            else{
                bootbox.alert('La película ya está entre tus favoritas.');
                $('#backdrop').addClass('none');
            }
        }
    });
}
function getFavorites(){
    $.ajax({
        url:'favorites',
        type:'GET',
        success: (data)=>{
            $('#list').html('');
            for(i=0;i<data.length;i++){
                let image=data[i].poster;
                if(data[i].poster=='N/A')
                    image="images/no-photo.jpg";
                $('#list').append(
                    '<div class="col-lg-3" style="height:500px;margin-bottom:30px">'+
                        '<div class="card">'+
                            '<img src="'+image+'" class="card-img-top" alt="" style="width:250px">'+
                            '<div class="card-body">'+
                                '<p><b>Título</b>: '+data[i].title+'</p>'+
                                '<p><b>Año</b>: '+data[i].year+'</p>'+
                                '<p><b>Rating</b>: '+data[i].rated+'</p>'+
                                '<p><button class="btn btn-sm btn-danger" onclick="deleteMovie('+data[i].id+')"> X </button></p>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
            }
        }
    });
}
function deleteMovie(id){
    bootbox.confirm({
        message: "¿Estás seguro de eliminar esta película?",
        buttons:{
            confirm:{
                label:"Si",
            },
            cancel:{
                label:"No",
            }
        },
        callback:(result)=>{
            if(result){
                $('#backdrop').removeClass('none');
                $.ajax({
                    url:'favorites/'+id,
                    type:'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (data)=>{
                        if(data.id!=0 && data.id!=null){
                            bootbox.alert('La película se eliminó correctamente.');
                            getFavorites();
                        }
                        else
                            bootbox.alert('Ocurrió un error al eliminar la película.');
                        $('#backdrop').addClass('none');
                    }
                });
            }
        }
    });
}