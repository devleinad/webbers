window.onload = function() {
    let _token = "{{csrf_token()}}";

    getAuthUserSelectedCategories();
    function getAuthUserSelectedCategories() {
        let url = "user/choice";
        axios
            .get(url, {
                action: "get_selected_categories"
            })
            .then(function(response) {
                if (response.success) {
                    $(".sidenav").html(result.data);
                }
            });
    }
};
