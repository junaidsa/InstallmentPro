$(document).ready(function () {
    const $container = $("#parent-screen-list");
    const csrf = $('meta[name="csrf-token"]').attr("content");

    const drake = dragula([$container[0]], {
        moves: function (el, source, handle, sibling) {
            return $(el).hasClass("dragable-item");
        },
    });

    drake.on("drop", function (el, target, source, sibling) {
        const order = [];

        $("#parent-screen-list > .dragable-item[data-screen-id]").each(
            function (index) {
                order.push({
                    screen_id: $(this).data("screen-id"),
                    sequence: index,
                });
            }
        );

        $.ajax({
            url: "/screenArrangement",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf,
            },
            contentType: "application/json",
            data: JSON.stringify({ order }),
            success: function (response) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                };
                if (response.success) {
                    toastr.success("Screen order updated!", "Success");
                } else {
                    toastr.error("Failed to save order.", "Error");
                }
            },
            error: function () {
                toastr.error(
                    "Something went wrong. Please try again.",
                    "Error"
                );
            },
        });
    });
});
