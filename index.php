<?php
    require 'BopApi.php';
    $isFindByName = (! empty($_GET['type']) && $_GET['type'] == 'byname');
    $locations = BopApi::getLocations();
    $results = [];

    if (! empty($_GET['inmateNumType']) && ! empty($_GET['inmate_num'])) {
        $results = BopApi::searchInmateById($_GET['inmateNumType'], $_GET['inmate_num']);
    }

    if (! empty($_GET['first_name']) && ! empty($_GET['last_name']) && $isFindByName) {
        $results = BopApi::searchInmateByDetails($_GET);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inmate Locator</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://www.bop.gov/mobile/find_inmate/css/mobileInmateLocator.min.css">
    <link rel="stylesheet" type="text/css" href="https://www.bop.gov/apis/bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://www.bop.gov/mobile/css/globalToMobile.css">
    <style>
        .inmate-result{padding:5px; background-color:#ffffff; border-radius:3px; margin:6px; line-height: 22px; font-size:16px; }
    </style>

</head>
<body>

    <div id="page_content">
        <h2 class="section-title">Find an inmate</h2>
        <ul class="nav nav-tabs">
            <li role="presentation" class=" <?php if (!$isFindByName): ?> active <?php endif; ?>"><a href="/">Find By Number</a></li>
            <li role="presentation" class=" <?php if ($isFindByName): ?> active <?php endif; ?>"><a href="?type=byname">Find By Name</a></li>
        </ul>

        <div id="inmate_search_area" class="container-fluid">
            <?php if ($isFindByName): ?>

            <form id="name_search_form">
                <div class="form-group">
                    <label for="inmate_first_name">First Name</label>
                    <input id="inmate_first_name" name="first_name" class="form-control input-lg" type="text" tabindex="1" autofocus="">
                </div>
                <div class="form-group">
                    <label for="inmate_middle_name">Middle Name</label>
                    <input id="inmate_middle_name" name="middle_name" class="form-control input-lg" type="text" tabindex="2">
                </div>
                <div class="form-group">
                    <label for="inmate_last_name">Last Name</label>
                    <input id="inmate_last_name" name="last_name" class="form-control input-lg" type="text" tabindex="3">
                </div>
                <div class="form-group">
                    <label for="inmate_race">Race</label>
                    <select id="inmate_race" class="form-control input-lg" tabindex="4" name="race">
                        <option value=""></option>
                        <option value="I">American Indian</option>
                        <option value="A">Asian</option>
                        <option value="B">Black</option>
                        <option value="W">White</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inmate_sex" tabindex="5">Sex</label>
                    <select id="inmate_sex" class="form-control input-lg" name="sex">
                        <option value=""></option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inmate_age" tabindex="6">Age</label>
                    <input id="inmate_age" name="age" class="form-control input-lg" type="text" maxlength="3">
                </div>
                <div class="form-group">
                    <label for="location" tabindex="5">Location</label>
                    <select id="location" class="form-control input-lg" name="location">
                        <option value="">Choose Location</option>
                        <?php foreach($locations as $l => $location): ?>
                        <option value="<?= $location->code ?>"><?= $location->nameTitle ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="type" value="byname">


                <div id="form_controls" class="form-section">
                    <input id="inmate_search_btn" type="submit" value="Search" tabindex="7" class="button">
                    <a href="#" class="link-style2" id="reset_link">Clear Form</a>
                </div>

                <div id="msgs"></div>
            </form>

            <?php else: ?>

            <form id="number_search_form">
                <div class="form-group">
                    <label for="inmate_num_type">Type of number</label>
                    <select id="inmate_num_type" name="inmateNumType" class="form-control input-lg" size="1">
                        <option value="IRN">BOP Register Number</option>
                        <option value="DCDC">DCDC Number</option>
                        <option value="FBI">FBI Number</option>
                        <option value="INS">INS Number</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inmate_num">Number</label>
                    <input id="inmate_num" type="text" required class="form-control input-lg" name="inmate_num">
                </div>
                <div id="form_controls">
                    <input id="inmate_search_btn" type="submit" value="Search" class="button">
                    <a href="#" class="link-style2" id="reset_link">Clear Form</a>
                </div>
            </form>

            <?php endif; ?>
        </div>

        <div id="inmate_results">
        <?php if (! empty($results)): ?>
            <?php foreach($results as $r => $inmate): ?>

                <div style="border-bottom: 1px solid #000;">

                    <ul>
                        <li><b>Last Name:</b> <?= $inmate->nameLast; ?></li>
                        <li><b>First Name:</b> <?= $inmate->nameFirst; ?></li>
                        <li><b>Middle Name:</b> <?= $inmate->nameMiddle; ?></li>
                        <li><b>Sex:</b> <?= $inmate->sex; ?></li>
                        <li><b>Race:</b> <?= $inmate->race; ?></li>
                        <li><b>Age:</b> <?= $inmate->age; ?></li>
                        <li><b>Inmate Number:</b> <?= $inmate->inmateNum; ?></li>
                        <li><b>Inmate Number Type:</b> <?= $inmate->inmateNumType; ?></li>
                        <li><b>Release Coded:</b> <?= $inmate->releaseCode; ?></li>
                        <li><b>Facility Code:</b> <?= $inmate->faclCode; ?></li>
                        <li><b>Facility Name:</b> <?= $inmate->faclName; ?></li>
                        <li><b>Facility Type:</b> <?= $inmate->faclType; ?></li>
                    </ul>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div id="msgs" style="display: block;">
                    <div class="msg error">No Inmate Found. Please ensure the "Type of Number" and the "Number" fields
                        are correct.</div>
                </div>
        <?php endif; ?>
        </div>

        <div class="clear"> </div>

    </div>


</body>
</html>