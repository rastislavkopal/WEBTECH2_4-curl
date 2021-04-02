<?php

require_once '/home/xkopalr1/public_html/zadanie4/models/ResourcesModel.php';

echo json_encode((new ResourcesModel())->getResourceNames());