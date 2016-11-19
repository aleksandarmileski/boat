$(function () {
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.currentPage) {
            var currentPage = sessionStorage.currentPage;
        } else {
            var currentPage = 1;
        }
    }
    var obj = $('#pagination').twbsPagination({
        totalPages: boatsNo,
        visiblePages: 10,
        startPage: currentPage,
        onPageClick: function (event, page) {
            // console.info(page);
            $('.boats').each(function (i, obj) {
                if (($(this).attr('id') < (page * 10)) && ($(this).attr('id') >= ((page - 1) * 10)  )) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('html, body').animate({
                scrollTop: "0px"
            }, 500);
            if (typeof(Storage) !== "undefined") {
                if (sessionStorage.currentPage) {
                    sessionStorage.currentPage = page;
                } else {
                    sessionStorage.currentPage = 1;
                }
                // console.log(sessionStorage.currentPage);
            }
        }
    });
//        console.info(obj.data());
});