$(function() {
    document.addEventListener('fullscreenchange', exitHandler);
    document.addEventListener('webkitfullscreenchange', exitHandler);
    document.addEventListener('mozfullscreenchange', exitHandler);
    document.addEventListener('MSFullscreenChange', exitHandler);

    $("#expand-table").on('click', function () {
        $(".scope-action-table-min").css("display", "block")
        $(".scope-action-table").css("display", "none")
        goFullScreen("row-table-expand")
    })

    $("#back-expand-table").on('click', function () {
        $(".scope-action-table").css("display", "block")
        $(".scope-action-table-min").css("display", "none")
        exitFullScreen()
    })

    function goFullScreen(temp) {
        var elem = document.getElementById(temp)
        if (elem.requestFullscreen) elem.requestFullscreen()
        else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen()
        else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen()
        else if (elem.msRequestFullscreen) elem.msRequestFullscreen()
    }

    function exitFullScreen() {
        if (document.exitFullscreen) document.exitFullscreen().catch(err => Promise.resolve(err))
        else if (document.mozCancelFullScreen) document.mozCancelFullScreen()
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen()
        else if (document.msExitFullscreen) document.msExitFullscreen()
    }

    function exitHandler() {
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
            $(".scope-action-table").css("display", "block")
            $(".scope-action-table-min").css("display", "none")
            exitFullScreen()
        }
    }
})
