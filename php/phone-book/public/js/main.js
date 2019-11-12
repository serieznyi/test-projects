$(document).on("click", "#select-image", function () {
    $(document).find("#file-input").focus().trigger("click");
});

$(document).on("change", "#file-input", function() {
    var $element = $(this)

    var fd = new FormData();
    var files = $(this)[0].files[0];
    fd.append('image',files);

    $.ajax({
        url: '/image/upload',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
            if(response.success == true){
                var $previewContainer = $element.parent().find(".preview");
                $previewContainer.find("*").remove();

                var $imagePreview = $('<img />',
                    { src: response.data.preview})
                    .appendTo($previewContainer)

                $imagePreview.attr("width", 150)
                $imagePreview.attr("height", 150)

                $("#image").attr("value", response.data.path)

            } else {
                console.log(response)
                alert('Upload file error');
            }
        },
    });

});