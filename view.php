<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="chosen_v1.8.7/chosen.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js"
            integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg=="
            crossorigin="anonymous"></script>
</head>
<body>
<div style="width: 300px">
    <canvas id="chartjs"></canvas>
</div>
<form action="." method="post">
    <?php if ($skills->num_rows > 0) { ?>
        <select name="skills[]" data-placeholder="Выберите категорию" class="chosen-select" multiple tabindex="4">
            <option value=""></option>
            <?php while ($row = $skills->fetch_assoc()) { ?>
                <option <?php if (@in_array($row['skill_id'], $_POST['skills'])) { ?> selected <?php } ?>
                        value="<?php echo $row['skill_id'] ?>"><?php echo $row['name'] ?></option>
            <?php } ?>
        </select>
    <?php }
    ?>
    <input type="submit" name="Filter" value="отфильтровать">
</form>
<?php if ($projects->num_rows > 0) { ?>
    <table style="width:100%">
        <tr>
            <th>Название проекта</th>
            <th>Бюджет</th>
            <th>Имя и логин заказчика</th>
        </tr>
        <?php while ($row = $projects->fetch_assoc()) { ?>
            <tr>
                <td><a href="<?php echo $row['link'] ?>"><?php echo $row['name'] ?></a></td>
                <td><?php echo $row['budget_amount'] ?></td>
                <td><?php echo $row['login'] ?><?php echo $row['first_name'] ?></td>
            </tr>
        <?php }
        ?>
    </table>
<?php } ?>

<script>
    new Chart(document.getElementById("chartjs"), {
        "type": "pie",
        "data": {
            "labels": ["до 500", "500-1000", "1000-5000", "более 5000"],
            "datasets": [{
//                "label": "My First Dataset",
                "data": [<?php echo $cnt_500?>, <?php echo $cnt_500_1000?>, <?php echo $cnt_1000_5000?>, <?php echo $cnt_5000?>],
                "backgroundColor": ["rgb(255, 99, 132)", "rgb(54, 162, 235)", "rgb(255, 205, 86)", "rgb(105, 50, 250)"]
            }]
        }
    });
</script>
<script src="chosen_v1.8.7/docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="chosen_v1.8.7/chosen.jquery.js" type="text/javascript"></script>
<script src="chosen_v1.8.7/docsupport/init.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>
