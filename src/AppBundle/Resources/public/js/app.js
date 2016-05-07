/**
 * Created by letunovskiymn on 07.05.16.
 */
$(function () {
    var currency = $(".currency");

    var round = function () {
        var currencies = [];
        $.each(currency, function (i, data) {
            currencies.push({name: $(data).data('name-currency'), source: $(data).data('source')});
        });
        $.ajax({
                url: "/ajax",
                data: {"currencies": currencies, date: new Date()},
                method: "GET",
                dataType: "json",
                beforeSend: function (xhr) {
                    currency.removeClass('animated bounceIn');
                }
            })
            .done(function (data) {
                //todo сделать проверку на одинаковые числа
                $.each(data, function (i, dataResponse) {
                    $("[data-source='" + dataResponse.source + "'][data-name-currency='" + dataResponse.name + "']").html(dataResponse.value);
                });
            })
            .fail(function () {
                alert("error");
            })
            .then(function () {
                currency.addClass('animated bounceIn');
            });

    }
    round();
    setInterval(round, 10000);
});