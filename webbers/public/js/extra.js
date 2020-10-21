var $ = jQuery.noConflict();
$(document).ready(function() {
    getAuthUserSelectedCategories();
    function getAuthUserSelectedCategories() {
        let user_id = "{{Auth::user()->id}}";
        let _token = "{{csrf_token()}}";
        $.ajax({
            url: "{{route('user_choice')}}",
            type: "GET",
            data: { action: "get_selected_categories" },
            success: function(result) {
                if (result.success) {
                    $(".sidenav").html(result.data);
                }
            }
        });
    }

    $(".comment-form").submit(function(event) {
        event.preventDefault();
        var ckeditor = CKEDITOR.instances.comment.getData();

        if (ckeditor == "") {
            $(".comment_error").text("Please enter something!");
        } else {
            var post_id = $("#post_id").val();
            var _token = $("#_token").val();
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: { comment: ckeditor, post_id: post_id, _token: _token },
                dataType: "json",
                success: function(result) {
                    if (result.success) {
                        document.window.location.reload();
                        ckeditor = " ";
                    } else {
                        ".error".html(
                            '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Action Failed!</strong> Your comment could not be submitted. Something is not right. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                        );
                    }
                }
            });
        }
    });
});
