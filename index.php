<?php

use Phone\Helpers\CountryHelper;
use Phone\Phone;

require './vendor/autoload.php';

/* ['country' => '251'] */
/* ['valid' => false] */
$list = Phone::list($_GET);
$countryList = CountryHelper::getCountries();
?>
<html>
    <head>Country Phones</head>
    <body>
        <h3>Phone Numbers</h3>
        <div>
            <form method="GET">
                <select name="country">
                    <option value="0">Select Country</option>
                    <?php
                        foreach ($countryList as $countryCode => $country) {
                            echo "<option value='$countryCode'>".$country['name']."</option>";
                        }
                    ?>
                </select>
                <select name="valid">
                    <option value="">Valid Phone Numbers</option>
                    <option value='1'>OK</option>
                    <option value='0'>NOK</option>
                </select>
                <button type="submit" value="submit">Submit</button>
            </form>
        </div>
        <div>
            <table>
                <tr>
                    <td>Country</td>
                    <td>State</td>
                    <td>Country Code</td>
                    <td>Phone Num.</td>
                </tr>
                <?php
                    foreach ($list as $phone) {
                        $country = $countryList[$phone['countryCode']]?? null;
                ?>
                    <tr>
                        <td><?php echo !empty($country)? $country['name']: ''; ?></td>
                        <td><?php echo !empty($country)? 'OK': 'NOK'; ?></td>
                        <td><?php echo $phone['countryCode']; ?></td>
                        <td><?php echo $phone['phone']; ?></td>
                    </tr>
                <?php
                    }
                ?>
            </table>
        </div>
    </body>
</html>