<?php
/* @var arary $repairs */
/* @var arary $params */

function person($repair, string $role)
{
    if ($repair["assigned_$role"]) {
        return $repair["assigned_$role"]->username . ' (' . $repair["assigned_{$role}_on"] . ')';
    } else {
        return '-';
    }
}

?>
<div class="container">
    <form action="/list" method="get" class="mb-5">
        <div class="field">
            <label class="label">Zakres dat</label>
            <div class="control">
                <input type="text" class="input" name="dates" value="<?= $params['dates'] ?>"/>
            </div>
        </div>
        <label class="checkbox mb-2">
            <input type="checkbox" name="finished"<?= $params['finished'] ? 'checked' : '' ?>>
            Tylko zakończone
        </label>
        <div>
            <button type="submit" class="button">Pokaż wyniki</button>
        </div>
    </form>


    <h1 class="title">Liczba napraw: <?= count($repairs) ?></h1>

    <?php if ($repairs): ?>
        <table class="table">
            <thead>
            <tr>
                <th>Numer naprawy</th>
                <th>ID Przyjmującego</th>
                <th>Data przyjęcia</th>
                <th>Model</th>
                <th>IMEI / SN</th>
                <th>Utworzona</th>
                <th>Przyjmujący</th>
                <th>Technik</th>
                <th>Kontrola</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($repairs as $repair): ?>
                <tr>
                    <td><?= $repair->nrNaprawy ?></td>
                    <td><?= $repair->idPrzyjmujacego ?></td>
                    <td><?= $repair->dataPrzyjecia ?></td>
                    <td><?= $repair->model ?></td>
                    <td><?= $repair->sn ?></td>
                    <td><?= $repair->createdAt ?></td>
                    <td><?= person($repair, 'p') ?></td>
                    <td><?= person($repair, 't') ?></td>
                    <td><?= person($repair, 'k') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<script>
    $('input[name="dates"]').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
</script>
