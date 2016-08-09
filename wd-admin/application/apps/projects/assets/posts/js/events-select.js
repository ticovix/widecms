$(function () {

    $(".chosen-select" ).chosen();

    var project = $("#data-project").data("project");
    var page = $("#data-project").data("page");
    var section = $("#data-project").data("section");
    $(".trigger-select").change(function () {
        var name = $(this).attr("name");
        var value = $(this).val();
        var index = $(".trigger-select").index(this);

        if ($("select").hasClass("trigger-" + name)) {

            var select = $(".trigger-" + name).not($(this).eq(index));
            var name_destination = select.attr("name");

            select.html($("<option>").val("").html(".."));
            $.ajax({
                url: app_path + 'posts/options-json',
                dataType: 'json',
                type: 'POST',
                data: {
                    project: project,
                    page: page,
                    section: section,
                    name_trigger: name,
                    name_destination: name_destination,
                    id_post: value
                },
                success: function (data) {
                    var total = data.length;
                    select.html($("<option>").val("").html("Selecione"));
                    for (var i = 0; i < total; i++) {
                        var dt = data[i];
                        var option = $("<option>").val(dt.value).html(dt.label);
                        select.append(option);
                    }
                    select.trigger("chosen:updated");
                }
            });
        }
    });
});