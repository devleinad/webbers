<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('post_content');

$(document).ready(function() {
    getAuthUserCategories();

    function getAuthUserCategories() {
        let user_id = "{{Auth::user()->id}}";
        let _token = "{{csrf_token()}}";
        $.ajax({
            url: "{{route('user_choice')}}",
            type: "GET",
            data: {
                action: 'get_selected_categories'
            },
            success: function(result) {
                if (result.success) {
                    $('.sidenav').html(result.data);
                }
            }
        });
    }
});


function getDifferentCategories() {
    let user_id = "{{Auth::user()->id}}";
    $.ajax({
        url: "{{route('user_choice')}}",
        type: "GET",
        data: {
            user_id: user_id,
            action: 'fetch_different_categories'
        },
        dataType: 'json',
        success: function(result) {
            if (result.success) {
                $(".categories_row").html(result.data);
            }
        }
    })
}



function selectCategory() {
    $(document.body).on('click', '.category', function() {
        let category_id = $(this).attr('data-id');
        let _token = "{{csrf_token()}}";
        $(this).toggleClass(['selected', 'bg-primary', 'text-white', 'border-0']);
        if ($(this).hasClass('selected')) {
            $.ajax({
                url: "{{route('user_choice')}}",
                type: 'POST',
                data: {
                    category_id: category_id,
                    action: 'selected',
                    _token: _token
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result.success);
                    //getUserCategoriesCount();
                    getDifferentCategories();
                    getAuthUserCategories();
                    getPosts();
                }

            });
        }


    });
}



function getPosts() {
    let action = "deliver_posts";
    let _token = "{{csrf_token()}}";
    $.ajax({
        url: "{{route('deliver_posts')}}",
        type: "GET",
        data: {
            action: action,
            _token: _token
        },
        dataType: "json",
        success: function(result) {
            if (result.success) {
                $('.post-container').html(result.data);
            }
        }
    });
}





$(document).ready(function() {
    getAuthUserSelectedCategories();

    function getAuthUserSelectedCategories() {
        let user_id = "{{Auth::user()->id}}";
        let _token = "{{csrf_token()}}";
        $.ajax({
            url: "{{route('user_choice')}}",
            type: "GET",
            data: {
                action: 'get_selected_categories'
            },
            success: function(result) {
                if (result.success) {
                    $('.sidenav').html(result.data);
                }
            }
        });

    }

    $(".close-ok-with-categories").click(function() {
        let action = $(this).attr('data-action');
        $.ajax({
            url: "{{route('user_choice')}}",
            type: "PATCH",
            data: {
                action: action,
                _token: "{{csrf_token()}}"
            },
            success: function(result) {
                if (result.success) {
                    $(".ok-with-categories").hide();
                }
            }
        })
    });

    $(".ushured").click(function() {
        let action = $(this).attr('data-action');
        $.ajax({
            url: "{{route('user_choice')}}",
            type: "PATCH",
            data: {
                action: action,
                _token: "{{csrf_token()}}"
            },
            success: function(result) {
                if (result.success) {
                    $(".alert-ushur").hide();
                }
            }
        })
    });


});
</script>

let comment_form = document.querySelector(".comment-form");
comment_form.addEventListener("submit", function() {
comment(e);
});

function comment(e) {
e.preventDefault();
let ckeditor = CKEDITOR.instances.comment.getData();
if (ckeditor == "") {
document.querySelector(".comment_error").textContent =
"Please enter something!";
} else {
let post_id = document.getElementById("post_id").value;
Axios.post(this.action, {
post_id: post_id,
comment: ckeditor,
_token: _token
}).then(function(response) {
if (response.success) {
window.reload();
ckeditor = "";
} else {
document.querySelector(
".error"
).innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Action Failed!</strong>
    Your comment could not be submitted. Something is not right. <button type="button" class="close"
        data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>`;
}
});
}
}

document
.querySelector(".close-ok-with-categories")
.addEventListener("click", function() {
let action = this.getAttribute("data-action");
let url = "{{route('user_choice')}}";
Axios.patch(url, { action: action, _token: _token }).then(function(
response
) {
if (response.success) {
document.querySelector(".ok-with-categories").hide();
}
});
});

document.querySelector(".ushured").addEventListener("click", function() {
let action = this.getAttribute("data-action");
let url = "{{route('user_choice')}}";
Axios.patch(url, { action: action, _token: _token }).then(function(
response
) {
if (response.success) {
document.querySelector(".alert-ushur").hide();
}
});
});