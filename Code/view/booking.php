<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 22.05.2023
 */

ob_start();

foreach ($location as $item) :
    $startDates = explode(',', $item['startDates']);
    $endDates = explode(',', $item['endDates']);
    ?>

    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-screen">
                <h1 class="text-5xl font-bold">Réservation de : <?= $item['name'] ?></h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <div class="divider-vertical"></div>
                <form action="index.php?action=booking&locationNumber=<?= $item['locationNumber'] ?>" method="post">
                    <div class="flex w-full justify-around">
                        <p class="label-text">Date de début</p>
                        <div class="divider-horizontal"></div>
                        <p class="label-text">Date de fin</p>
                    </div>
                    <div class="flex w-full">
                        <input id="startDatepicker"
                               name="inputStartDate"
                               class="input input-bordered w-full" type="text"
                               autocomplete="off"
                               onchange="minEndDate(this.value)" readonly required>
                        <div class="divider-horizontal"></div>
                        <input id="endDatepicker" name="inputEndDate"
                               class="input input-bordered w-full" type="text"
                               autocomplete="off" onchange="totalPrice(this.value)" readonly required>
                    </div>
                    <br>
                    <div class="flex w-full justify-between">
                        <h3 class="text-xl font-bold">Prix total :</h3>
                        <div class="divider-horizontal"></div>
                        <div class="flex">
                            <h3 id="totalPrice" class="text-xl font-bold">0.00</h3>
                            <input id="inputTotalPrice" name="inputTotalPrice" type="hidden">
                            <h3 class="text-xl font-bold">&nbsp;CHF</h3>
                        </div>
                    </div>
                    <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                    <div class="form-control mt-6">
                        <input type="submit" value="Confirmer la réservation !" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var startDate;
        var date_range = [ <?php
            $dateCount = 0;
            foreach ($startDates as $startDate):
            ?>
            ["<?= $startDate ?>", "<?= $endDates[$dateCount] ?>"],
            <?php
            $dateCount += 1;
            endforeach; ?>
        ];

        function printClientsRange(rangeValue) {
            document.getElementById("clientsRangeValue").innerHTML = rangeValue;
        }

        function minEndDate(startDateValue) {
            startDate = new Date(startDateValue);
            var minEndDate = new Date(startDate.getTime() + (24 * 60 * 60 * 1000));

            $("#endDatepicker").datepicker("option", "minDate", minEndDate);
            $("#endDatepicker").datepicker({
                beforeShowDay: function (date) {
                    var string = $.datepicker.formatDate('mm-dd-yy', date);

                    for (var i = 0; i < date_range.length; i++) {
                        if (Array.isArray(date_range[i])) {
                            var from = new Date(date_range[i][0]);
                            var to = new Date(date_range[i][1]);
                            var current = new Date(string);

                            if (current >= from && current <= to) {
                                return [false, 'unselectable-date'];
                            }
                        }
                    }

                    return [true, ''];
                }
            });
        }

        $("#startDatepicker").datepicker({
            minDate: 0,
            beforeShowDay: function (date) {
                var string = $.datepicker.formatDate('mm-dd-yy', date);

                for (var i = 0; i < date_range.length; i++) {
                    if (Array.isArray(date_range[i])) {
                        var from = new Date(date_range[i][0]);
                        var to = new Date(date_range[i][1]);
                        var current = new Date(string);

                        if (current >= from && current <= to) {
                            return [false, 'unselectable-date'];
                        }
                    }
                }

                return [true, ''];
            }
        });

        $("#endDatepicker").datepicker({
            beforeShowDay: function (date) {
                var string = $.datepicker.formatDate('mm-dd-yy', date);
                var selectedStartDate = $("#startDatepicker").datepicker("getDate");

                if (selectedStartDate && date < selectedStartDate) {
                    return [false, 'unselectable-date'];
                }

                for (var i = 0; i < date_range.length; i++) {
                    if (Array.isArray(date_range[i])) {
                        var from = new Date(date_range[i][0]);
                        var to = new Date(date_range[i][1]);
                        var current = new Date(string);

                        if (current >= from && current <= to) {
                            return [false, 'unselectable-date'];
                        }
                    }
                }

                return [true, ''];
            }
        });

        function totalPrice(endDateValue){
            endDate = new Date(endDateValue);
            var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
            var numberOfNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            var pricePerNight = <?= $item['pricePerNight'] ?>

            var totalPriceElement = document.getElementById('totalPrice');
            var totalPriceInput = document.getElementById('inputTotalPrice');

            totalPriceElement.innerHTML = numberOfNights * pricePerNight;
            totalPriceInput.value = numberOfNights * pricePerNight;
        }

        window.onload = function showErrorMessage() {
            <?php if (isset($reservationErrorMessage)) :?>
            alert("<?= $reservationErrorMessage ?>");
            <?php endif;?>
        }
    </script>

<?php
endforeach;
$content = ob_get_clean();
require "gabarit.php";
?>